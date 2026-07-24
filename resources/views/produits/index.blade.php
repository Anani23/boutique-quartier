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
                    <th></th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix vente</th>
                    <th>Stock</th>
                    <th>QR</th>
                    @if (auth()->user()->isGerant())
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($produits as $produit)
                    <tr>
                        <td>
                            <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" width="48" height="48" class="rounded" style="object-fit: cover;">
                        </td>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ $produit->categorie->nom ?? '—' }}</td>
                        <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge {{ $produit->en_rupture ? 'bg-danger' : 'bg-success' }}">
                                {{ $produit->quantite_stock }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal" data-bs-target="#modal-qr"
                                    data-code="{{ $produit->code_qr }}" data-nom="{{ $produit->nom }}">
                                <i class="ti ti-qrcode"></i>
                            </button>
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
                    <tr><td colspan="7" class="text-center text-muted py-4">Aucun produit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $produits->links() }}</div>

<div class="modal modal-blur fade" id="modal-qr" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-qr-titre">QR produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="modal-qr-canvas" class="d-inline-block"></div>
                <p class="text-secondary mt-3 mb-0">À scanner depuis "Nouvelle vente" pour ajouter ce produit au panier.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.getElementById('modal-qr').addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const qrCanvas = document.getElementById('modal-qr-canvas');
        document.getElementById('modal-qr-titre').textContent = btn.dataset.nom;
        qrCanvas.innerHTML = '';
        new QRCode(qrCanvas, {
            text: btn.dataset.code,
            width: 200,
            height: 200,
        });
    });
</script>
@endpush
