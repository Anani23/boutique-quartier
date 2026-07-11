@extends('layouts.app')

@section('title', 'Inscrire ma boutique')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center">🏪 Inscrire ma boutique</h4>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h6 class="text-muted">Votre boutique</h6>
                    <div class="mb-3">
                        <label class="form-label">Nom de la boutique</label>
                        <input type="text" name="boutique_nom" value="{{ old('boutique_nom') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse (optionnel)</label>
                        <input type="text" name="boutique_adresse" value="{{ old('boutique_adresse') }}" class="form-control">
                    </div>

                    <h6 class="text-muted mt-4">Votre compte gérant</h6>
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">Créer ma boutique</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
