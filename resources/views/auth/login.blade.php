@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center">🏪 Boutique Quartier</h4>
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
</div>
@endsection
