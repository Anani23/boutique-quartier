@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')
<h4 class="mb-4">Ajouter un produit</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('produits.store') }}">
            @csrf
            @include('produits._form', ['produit' => null])
            <button class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('produits.index') }}" class="btn btn-link">Annuler</a>
        </form>
    </div>
</div>
@endsection
