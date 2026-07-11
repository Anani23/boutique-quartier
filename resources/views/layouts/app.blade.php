<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boutique Quartier')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6f8; }
        .navbar-brand { font-weight: 600; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stat-card { border-radius: .75rem; }
        table.table thead { background-color: #f1f3f5; }
    </style>
    @stack('styles')
</head>
<body>
@auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">🏪 {{ auth()->user()->boutique->nom }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ventes.create') }}">Nouvelle vente</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ventes.index') }}">Historique ventes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('produits.index') }}">Produits</a></li>
                    @if (auth()->user()->isGerant())
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Catégories</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('rapports.index') }}">Rapports</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('utilisateurs.index') }}">Vendeurs</a></li>
                    @endif
                </ul>
                <span class="navbar-text me-3 text-white-50">{{ auth()->user()->name }} · {{ auth()->user()->role === 'gerant' ? 'Gérant' : 'Vendeur' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>
@endauth

<div class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
