@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Produits</h4>
    @if (auth()->user()->isGerant())
        <a href="{{ route('produits.create') }}" class="btn btn-primary">+ Ajouter un produit</a>
    @endif
</div>

<form method="GET" class="mb-3">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un produit...">
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix vente</th>
                    <th>Stock</th>
                    @if (auth()->user()->isGerant())
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($produits as $produit)
                    <tr>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ $produit->categorie->nom ?? '—' }}</td>
                        <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge {{ $produit->en_rupture ? 'bg-danger' : 'bg-success' }}">
                                {{ $produit->quantite_stock }}
                            </span>
                        </td>
                        @if (auth()->user()->isGerant())
                            <td class="text-end">
                                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun produit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $produits->links() }}</div>
@endsection
