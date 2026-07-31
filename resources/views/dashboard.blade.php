@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="card mb-4 border-0 position-relative overflow-hidden text-white"
     style="background-image: url('https://picsum.photos/id/42/1200/300'); background-size: cover; background-position: center; min-height: 160px;">
    <div class="position-absolute top-0 start-0 end-0 bottom-0"
         style="background: linear-gradient(90deg, rgba(22,33,63,0.92) 0%, rgba(22,33,63,0.55) 60%, rgba(22,33,63,0.25) 100%);"></div>
    <div class="card-body position-relative d-flex flex-column justify-content-center">
        <h3 class="mb-1 text-white">Bonjour, {{ auth()->user()->name }}</h3>
        <p class="mb-0 opacity-75">Voici un aperçu de {{ auth()->user()->boutique->nom }} aujourd'hui.</p>
    </div>
</div>

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
