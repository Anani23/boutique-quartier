@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<h4 class="mb-4">Rapports & statistiques</h4>

<form method="GET" class="d-flex gap-2 mb-4">
    <input type="date" name="debut" value="{{ $debut->toDateString() }}" class="form-control" style="max-width: 200px;">
    <input type="date" name="fin" value="{{ $fin->toDateString() }}" class="form-control" style="max-width: 200px;">
    <button class="btn btn-outline-secondary">Filtrer</button>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Chiffre d'affaires sur la période</div>
                <div class="fs-3 fw-bold">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Nombre de ventes</div>
                <div class="fs-3 fw-bold">{{ $nombreVentes }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">🏆 Produits les plus vendus</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Produit</th><th>Qté vendue</th><th>Montant</th></tr></thead>
                    <tbody>
                        @forelse ($meilleursProduits as $p)
                            <tr>
                                <td>{{ $p->nom_produit }}</td>
                                <td>{{ $p->total_quantite }}</td>
                                <td>{{ number_format($p->total_montant, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucune donnée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">📈 Ventes par jour</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Date</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse ($ventesParJour as $v)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($v->jour)->format('d/m/Y') }}</td>
                                <td>{{ number_format($v->total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">Aucune donnée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body py-2">
                {{ $ventesParJour->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
