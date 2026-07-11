<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $ventes = Vente::with('vendeur')
            ->where('boutique_id', $this->boutiqueId())
            ->when($request->filled('date'), fn ($q) => $q->whereDate('created_at', $request->date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('ventes.index', compact('ventes'));
    }

    public function create()
    {
        $produits = Produit::where('boutique_id', $this->boutiqueId())
            ->where('quantite_stock', '>', 0)
            ->orderBy('nom')
            ->get();

        $produitsJson = $produits->map(function ($produit) {
            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'prix' => (float) $produit->prix_vente,
                'stock' => $produit->quantite_stock,
            ];
        })->values();

        return view('ventes.create', compact('produits', 'produitsJson'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lignes' => ['required', 'array', 'min:1'],
            'lignes.*.produit_id' => [
                'required',
                Rule::exists('produits', 'id')->where('boutique_id', $this->boutiqueId()),
            ],
            'lignes.*.quantite' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $vente = DB::transaction(function () use ($data) {
                $vente = Vente::create([
                    'boutique_id' => $this->boutiqueId(),
                    'user_id' => Auth::id(),
                    'numero_recu' => 'R-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                    'total' => 0,
                ]);

                $total = 0;

                foreach ($data['lignes'] as $ligne) {
                    $produit = Produit::where('boutique_id', $this->boutiqueId())
                        ->lockForUpdate()
                        ->findOrFail($ligne['produit_id']);

                    if ($produit->quantite_stock < $ligne['quantite']) {
                        throw ValidationException::withMessages([
                            'lignes' => "Stock insuffisant pour {$produit->nom} (disponible : {$produit->quantite_stock}).",
                        ]);
                    }

                    $sousTotal = $produit->prix_vente * $ligne['quantite'];

                    $vente->lignes()->create([
                        'produit_id' => $produit->id,
                        'nom_produit' => $produit->nom,
                        'quantite' => $ligne['quantite'],
                        'prix_unitaire' => $produit->prix_vente,
                        'sous_total' => $sousTotal,
                    ]);

                    $produit->decrement('quantite_stock', $ligne['quantite']);
                    $total += $sousTotal;
                }

                $vente->update(['total' => $total]);

                return $vente;
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente enregistrée.');
    }

    public function show(Vente $vente)
    {
        abort_unless($vente->boutique_id === $this->boutiqueId(), 403);
        $vente->load('lignes', 'vendeur', 'boutique');

        return view('ventes.recu', compact('vente'));
    }

    public function pdf(Vente $vente)
    {
        abort_unless($vente->boutique_id === $this->boutiqueId(), 403);
        $vente->load('lignes', 'vendeur', 'boutique');

        $pdf = Pdf::loadView('ventes.recu-pdf', compact('vente'))->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->download($vente->numero_recu.'.pdf');
    }
}
