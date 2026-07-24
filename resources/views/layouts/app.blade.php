<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boutique Quartier')</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --tblr-primary: #22315A;
            --tblr-primary-rgb: 34, 49, 90;
        }
        .navbar-brand-image { font-size: 1.5rem; }
        .stat-card { border-radius: .75rem; }
        table.table thead { background-color: var(--tblr-bg-surface-secondary); }
        .nav-link.active { font-weight: 600; }

        /* Fond animé discret, présent sur toute l'application */
        body { background-color: transparent !important; position: relative; }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(135deg, rgba(34,49,90,0.07) 0%, rgba(169,119,42,0.06) 45%, rgba(34,49,90,0.07) 100%);
            background-size: 300% 300%;
            animation: fondDefilant 28s ease infinite;
        }
        @keyframes fondDefilant {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @media (prefers-reduced-motion: reduce) {
            body::before { animation: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="page">
@auth
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
                    <i class="ti ti-building-store"></i> {{ auth()->user()->boutique->nom }}
                </a>
            </h1>
            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon"><i class="ti ti-layout-dashboard"></i></span>
                            <span class="nav-link-title">Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('ventes.create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('ventes.create') }}">
                            <span class="nav-link-icon"><i class="ti ti-shopping-cart-plus"></i></span>
                            <span class="nav-link-title">Nouvelle vente</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('ventes.index') || request()->routeIs('ventes.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('ventes.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-receipt"></i></span>
                            <span class="nav-link-title">Historique ventes</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('produits.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-package"></i></span>
                            <span class="nav-link-title">Produits</span>
                        </a>
                    </li>
                    @if (auth()->user()->isGerant())
                        <li class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('categories.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-tags"></i></span>
                                <span class="nav-link-title">Catégories</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rapports.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-chart-bar"></i></span>
                                <span class="nav-link-title">Rapports</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('utilisateurs.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                                <span class="nav-link-title">Vendeurs</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('abonnement.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('abonnement.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-credit-card"></i></span>
                                <span class="nav-link-title">
                                    Abonnement
                                    @if (auth()->user()->boutique->abonnement_statut === 'essai' && auth()->user()->boutique->joursEssaiRestants() <= 3)
                                        <span class="badge bg-yellow-lt ms-1">{{ auth()->user()->boutique->joursEssaiRestants() }}j</span>
                                    @elseif (auth()->user()->boutique->abonnement_statut === 'expire')
                                        <span class="badge bg-red-lt ms-1">Expiré</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </aside>
@endauth

<div class="page-wrapper">
    @auth
        <div class="navbar navbar-expand-md d-print-none border-bottom">
            <div class="container-xl">
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="text-secondary">
                        {{ auth()->user()->name }}
                        <span class="badge bg-blue-lt ms-1">{{ auth()->user()->role === 'gerant' ? 'Gérant' : 'Vendeur' }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-logout"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <div class="page-body">
        <div class="container-xl">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
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
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
@stack('scripts')
</body>
</html>
