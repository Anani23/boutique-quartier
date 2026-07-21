<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique->accesActif()) {
            if ($boutique->abonnement_statut !== \App\Models\Boutique::STATUT_EXPIRE) {
                $boutique->update(['abonnement_statut' => \App\Models\Boutique::STATUT_EXPIRE]);
            }

            return redirect()->route('abonnement.index')
                ->with('error', "Votre période d'essai ou votre abonnement est terminé. Choisissez une formule pour continuer à utiliser Boutique Quartier.");
        }

        return $next($request);
    }
}
