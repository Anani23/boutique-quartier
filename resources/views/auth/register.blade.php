@extends('layouts.app')

@section('title', 'Inscrire ma boutique')

@section('content')
<div class="row g-0 shadow-sm rounded overflow-hidden my-4" style="min-height: 70vh;">
    <div class="col-md-5 d-none d-md-block position-relative"
         style="background-image: url('https://picsum.photos/id/1080/900/1200'); background-size: cover; background-position: center;">
        <div class="position-absolute bottom-0 start-0 end-0 p-5 text-white"
             style="background: linear-gradient(180deg, rgba(22,33,63,0) 0%, rgba(22,33,63,0.9) 100%);">
            <h2 class="mb-2"><i class="ti ti-building-store"></i> Boutique Quartier</h2>
            <p class="mb-0 opacity-75">Essai gratuit de 14 jours, sans carte bancaire.</p>
        </div>
    </div>
    <div class="col-md-7 d-flex align-items-center bg-white">
        <div class="w-100 p-4 p-md-5">
            <h3 class="mb-4">Inscrire ma boutique</h3>
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
@endsection
