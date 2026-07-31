@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row g-0 shadow-sm rounded overflow-hidden my-4" style="min-height: 70vh;">
    <div class="col-md-6 d-none d-md-block position-relative"
         style="background-image: url('https://picsum.photos/id/292/900/1200'); background-size: cover; background-position: center;">
        <div class="position-absolute bottom-0 start-0 end-0 p-5 text-white"
             style="background: linear-gradient(180deg, rgba(22,33,63,0) 0%, rgba(22,33,63,0.9) 100%);">
            <h2 class="mb-2"><i class="ti ti-building-store"></i> Boutique Quartier</h2>
            <p class="mb-0 opacity-75">Vente, stock et rapports pour votre commerce, sans complication.</p>
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-center bg-white">
        <div class="w-100 p-4 p-md-5">
            <h3 class="mb-4">Connexion</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
                <button class="btn btn-primary w-100">Se connecter</button>
            </form>
            <p class="text-center mt-3 mb-0">
                Pas encore de boutique ? <a href="{{ route('register') }}">Inscrire ma boutique</a>
            </p>
        </div>
    </div>
</div>
@endsection
