@php $p = $produit; @endphp
<div class="mb-3">
    <label class="form-label">Nom</label>
    <input type="text" name="nom" value="{{ old('nom', $p->nom ?? '') }}" class="form-control" required autofocus>
</div>
<div class="mb-3">
    <label class="form-label">Description (optionnel)</label>
    <input type="text" name="description" value="{{ old('description', $p->description ?? '') }}" class="form-control">
</div>
<div class="mb-3">
    <label class="form-label">Catégorie</label>
    <select name="categorie_id" class="form-select">
        <option value="">— Aucune —</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('categorie_id', $p->categorie_id ?? null) == $cat->id)>{{ $cat->nom }}</option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Prix d'achat</label>
        <input type="number" step="0.01" min="0" name="prix_achat" value="{{ old('prix_achat', $p->prix_achat ?? 0) }}" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Prix de vente</label>
        <input type="number" step="0.01" min="0" name="prix_vente" value="{{ old('prix_vente', $p->prix_vente ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Quantité en stock</label>
        <input type="number" min="0" name="quantite_stock" value="{{ old('quantite_stock', $p->quantite_stock ?? 0) }}" class="form-control" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Seuil d'alerte stock bas</label>
    <input type="number" min="0" name="seuil_alerte" value="{{ old('seuil_alerte', $p->seuil_alerte ?? 5) }}" class="form-control" required>
</div>
