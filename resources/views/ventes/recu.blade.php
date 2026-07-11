@extends('layouts.app')

@section('title', 'Reçu ' . $vente->numero_recu)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" id="recu">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h5 class="mb-0">{{ $vente->boutique->nom }}</h5>
                    <small class="text-muted">{{ $vente->boutique->adresse }}</small>
                </div>
                <hr>
                <div class="d-flex justify-content-between"><span>Reçu</span><strong>{{ $vente->numero_recu }}</strong></div>
                <div class="d-flex justify-content-between"><span>Date</span><span>{{ $vente->created_at->format('d/m/Y H:i') }}</span></div>
                <div class="d-flex justify-content-between"><span>Vendeur</span><span>{{ $vente->vendeur->name }}</span></div>
                <hr>
                <table class="table table-sm">
                    <thead><tr><th>Produit</th><th>Qté</th><th class="text-end">Sous-total</th></tr></thead>
                    <tbody>
                        @foreach ($vente->lignes as $ligne)
                            <tr>
                                <td>{{ $ligne->nom_produit }}</td>
                                <td>{{ $ligne->quantite }}</td>
                                <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Total</span>
                    <span>{{ number_format($vente->total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
        <div class="text-center mt-3 no-print">
            <button onclick="window.print()" class="btn btn-outline-secondary">Imprimer</button>
            <a href="{{ route('ventes.pdf', $vente) }}" class="btn btn-outline-secondary">Télécharger PDF</a>
            <a href="{{ route('ventes.create') }}" class="btn btn-primary">Nouvelle vente</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .navbar, .no-print { display: none !important; }
    }
</style>
@endpush
