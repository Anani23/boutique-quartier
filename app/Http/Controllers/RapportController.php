<?php

namespace App\Http\Controllers;

use App\Models\LigneVente;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $debut = $request->filled('debut')
            ? Carbon::parse($request->debut)->startOfDay()
            : now()->startOfMonth();
        $fin = $request->filled('fin')
            ? Carbon::parse($request->fin)->endOfDay()
            : now()->endOfDay();

        $ventesQuery = Vente::where('boutique_id', $this->boutiqueId())
            ->whereBetween('created_at', [$debut, $fin]);

        $chiffreAffaires = (clone $ventesQuery)->sum('total');
        $nombreVentes = (clone $ventesQuery)->count();

        $meilleursProduits = LigneVente::whereHas('vente', function ($q) use ($debut, $fin) {
                $q->where('boutique_id', $this->boutiqueId())->whereBetween('created_at', [$debut, $fin]);
            })
            ->selectRaw('nom_produit, SUM(quantite) as total_quantite, SUM(sous_total) as total_montant')
            ->groupBy('nom_produit')
            ->orderByDesc('total_quantite')
            ->limit(10)
            ->get();

        $ventesParJour = (clone $ventesQuery)
            ->selectRaw('DATE(created_at) as jour, SUM(total) as total')
            ->groupBy('jour')
            ->orderByDesc('jour')
            ->paginate(7)
            ->withQueryString();

        return view('rapports.index', compact(
            'chiffreAffaires',
            'nombreVentes',
            'meilleursProduits',
            'ventesParJour',
            'debut',
            'fin'
        ));
    }
}
