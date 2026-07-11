@extends('layouts.app')

@section('title', 'Historique des ventes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Historique des ventes</h4>
    <a href="{{ route('ventes.create') }}" class="btn btn-primary">+ Nouvelle vente</a>
</div>

<form method="GET" class="mb-3 d-flex gap-2">
    <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="max-width: 220px;">
    <button class="btn btn-outline-secondary">Filtrer</button>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr><th>Reçu</th><th>Vendeur</th><th>Date</th><th>Total</th></tr>
            </thead>
            <tbody>
                @forelse ($ventes as $vente)
                    <tr>
                        <td><a href="{{ route('ventes.show', $vente) }}">{{ $vente->numero_recu }}</a></td>
                        <td>{{ $vente->vendeur->name }}</td>
                        <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($vente->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune vente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $ventes->links() }}</div>
@endsection
