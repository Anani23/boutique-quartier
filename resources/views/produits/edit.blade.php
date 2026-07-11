@extends('layouts.app')

@section('title', 'Modifier le produit')

@section('content')
<h4 class="mb-4">Modifier « {{ $produit->nom }} »</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('produits.update', $produit) }}">
            @csrf @method('PUT')
            @include('produits._form', ['produit' => $produit])
            <button class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('produits.index') }}" class="btn btn-link">Annuler</a>
        </form>
    </div>
</div>
@endsection
