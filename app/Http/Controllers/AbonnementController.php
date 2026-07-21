<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Paiement;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AbonnementController extends Controller
{
    public function __construct(private CinetPayService $cinetPay)
    {
    }

    public function index()
    {
        $boutique = auth()->user()->boutique;
        $paiements = $boutique->paiements()->latest()->limit(10)->get();

        return view('abonnement.index', [
            'boutique' => $boutique,
            'paiements' => $paiements,
            'cinetPayConfigure' => $this->cinetPay->estConfigure(),
        ]);
    }

    public function payer(Request $request)
    {
        $data = $request->validate([
            'plan' => ['required', 'in:mensuel,annuel'],
        ]);

        if (! $this->cinetPay->estConfigure()) {
            return back()->with('error', "Le paiement en ligne n'est pas encore configuré. Ajoutez vos identifiants CinetPay (CINETPAY_API_KEY / CINETPAY_SITE_ID) dans le fichier .env pour l'activer.");
        }

        $boutique = auth()->user()->boutique;
        $montant = Paiement::PRIX[$data['plan']];
        $transactionId = 'ABO-'.$boutique->id.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));

        $paiement = Paiement::create([
            'boutique_id' => $boutique->id,
            'plan' => $data['plan'],
            'montant' => $montant,
            'transaction_id' => $transactionId,
            'statut' => Paiement::STATUT_EN_ATTENTE,
        ]);

        try {
            $resultat = $this->cinetPay->initierPaiement([
                'transaction_id' => $transactionId,
                'montant' => $montant,
                'description' => 'Abonnement Boutique Quartier — '.$data['plan'],
                'nom' => auth()->user()->name,
                'telephone' => $boutique->telephone,
                'return_url' => route('abonnement.retour', ['transaction_id' => $transactionId]),
            ]);
        } catch (\Throwable $e) {
            Log::error('Échec initiation paiement CinetPay', ['message' => $e->getMessage()]);

            return back()->with('error', "Impossible de contacter la passerelle de paiement pour le moment. Réessayez dans quelques instants.");
        }

        $paiement->update(['payment_url' => $resultat['payment_url']]);

        return redirect()->away($resultat['payment_url']);
    }

    public function retour(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $paiement = Paiement::where('transaction_id', $transactionId)->firstOrFail();

        $this->reconcilier($paiement);

        return view('abonnement.retour', ['paiement' => $paiement->fresh()]);
    }

    public function webhook(Request $request)
    {
        $transactionId = $request->input('cpm_trans_id') ?? $request->input('transaction_id');

        $paiement = Paiement::where('transaction_id', $transactionId)->first();

        if ($paiement) {
            $this->reconcilier($paiement);
        }

        return response('OK', 200);
    }

    /**
     * Ne fait jamais confiance au contenu brut du webhook : on revérifie toujours
     * le statut réel auprès de CinetPay avant de mettre à jour quoi que ce soit.
     */
    private function reconcilier(Paiement $paiement): void
    {
        if ($paiement->statut !== Paiement::STATUT_EN_ATTENTE) {
            return;
        }

        $statut = $this->cinetPay->verifierStatut($paiement->transaction_id);

        if ($statut['status'] === 'ACCEPTED') {
            $paiement->update([
                'statut' => Paiement::STATUT_REUSSI,
                'mode_paiement' => $statut['payment_method'],
            ]);

            $boutique = $paiement->boutique;
            $dureeAjout = $paiement->plan === 'annuel' ? now()->addYear() : now()->addMonth();
            $baseExpiration = $boutique->abonnement_expire_le && $boutique->abonnement_expire_le->isFuture()
                ? $boutique->abonnement_expire_le
                : now();

            $boutique->update([
                'plan' => $paiement->plan,
                'abonnement_statut' => Boutique::STATUT_ACTIF,
                'abonnement_expire_le' => $paiement->plan === 'annuel'
                    ? $baseExpiration->copy()->addYear()
                    : $baseExpiration->copy()->addMonth(),
            ]);
        } elseif (in_array($statut['status'], ['REFUSED', 'CANCELLED'], true)) {
            $paiement->update(['statut' => Paiement::STATUT_ECHOUE]);
        }
    }
}
