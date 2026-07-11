@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<h4 class="mb-4">Tableau de bord</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Chiffre d'affaires du jour</div>
                <div class="fs-3 fw-bold">{{ number_format($caJour, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Chiffre d'affaires du mois</div>
                <div class="fs-3 fw-bold">{{ number_format($caMois, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Ventes aujourd'hui</div>
                <div class="fs-3 fw-bold">{{ $ventesJour }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">⚠️ Stock bas</div>
            <div class="card-body">
                @forelse ($produitsEnRupture as $produit)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $produit->nom }}</span>
                        <span class="badge {{ $produit->quantite_stock == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                            {{ $produit->quantite_stock }} en stock
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune alerte de stock.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">🧾 Dernières ventes</div>
            <div class="card-body">
                @forelse ($dernieresVentes as $vente)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>
                            <a href="{{ route('ventes.show', $vente) }}">{{ $vente->numero_recu }}</a>
                            <small class="text-muted">— {{ $vente->vendeur->name }}</small>
                        </span>
                        <span>{{ number_format($vente->total, 0, ',', ' ') }} FCFA</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune vente pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
