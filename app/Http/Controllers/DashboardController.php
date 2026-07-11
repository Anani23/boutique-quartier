<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $boutiqueId = $this->boutiqueId();

        $caJour = Vente::where('boutique_id', $boutiqueId)->whereDate('created_at', today())->sum('total');
        $caMois = Vente::where('boutique_id', $boutiqueId)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('total');
        $ventesJour = Vente::where('boutique_id', $boutiqueId)->whereDate('created_at', today())->count();

        $produitsEnRupture = Produit::where('boutique_id', $boutiqueId)
            ->whereColumn('quantite_stock', '<=', 'seuil_alerte')
            ->orderBy('quantite_stock')
            ->limit(10)
            ->get();

        $dernieresVentes = Vente::with('vendeur')
            ->where('boutique_id', $boutiqueId)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'caJour',
            'caMois',
            'ventesJour',
            'produitsEnRupture',
            'dernieresVentes'
        ));
    }
}
