@extends('layouts.app')

@section('title', 'Abonnement')

@section('content')
<h2 class="mb-4">Abonnement</h2>

<div class="row row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="text-secondary mb-1">Statut actuel</div>
                    @if ($boutique->abonnement_statut === 'actif')
                        <div class="h3 mb-0">
                            <span class="badge bg-green-lt">Actif</span>
                            Plan {{ ucfirst($boutique->plan) }}
                        </div>
                        <div class="text-secondary mt-1">Expire le {{ $boutique->abonnement_expire_le->format('d/m/Y') }}</div>
                    @elseif ($boutique->abonnement_statut === 'essai')
                        <div class="h3 mb-0">
                            <span class="badge bg-blue-lt">Essai gratuit</span>
                            {{ $boutique->joursEssaiRestants() }} jour(s) restant(s)
                        </div>
                        <div class="text-secondary mt-1">Se termine le {{ $boutique->essai_expire_le->format('d/m/Y') }}</div>
                    @else
                        <div class="h3 mb-0">
                            <span class="badge bg-red-lt">Expiré</span>
                        </div>
                        <div class="text-secondary mt-1">Choisissez une formule ci-dessous pour continuer.</div>
                    @endif
                </div>
                @if (! $cinetPayConfigure)
                    <div class="alert alert-warning mb-0 py-2 px-3">
                        <i class="ti ti-alert-triangle"></i>
                        Paiement en ligne non configuré (CINETPAY_API_KEY manquant dans .env)
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-secondary">Formule mensuelle</div>
                <div class="h1 mt-2 mb-3">2 000 <small class="text-secondary fs-4">FCFA / mois</small></div>
                <ul class="list-unstyled text-secondary mb-4">
                    <li><i class="ti ti-check text-green"></i> Toutes les fonctionnalités incluses</li>
                    <li><i class="ti ti-check text-green"></i> Sans engagement</li>
                </ul>
                <form method="POST" action="{{ route('abonnement.payer') }}">
                    @csrf
                    <input type="hidden" name="plan" value="mensuel">
                    <button class="btn btn-primary w-100" @disabled(! $cinetPayConfigure)>Payer avec CinetPay</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="text-secondary">Formule annuelle</div>
                    <span class="badge bg-primary-lt">2 mois offerts</span>
                </div>
                <div class="h1 mt-2 mb-3">20 000 <small class="text-secondary fs-4">FCFA / an</small></div>
                <ul class="list-unstyled text-secondary mb-4">
                    <li><i class="ti ti-check text-green"></i> Toutes les fonctionnalités incluses</li>
                    <li><i class="ti ti-check text-green"></i> Équivalent à 1 667 FCFA / mois</li>
                </ul>
                <form method="POST" action="{{ route('abonnement.payer') }}">
                    @csrf
                    <input type="hidden" name="plan" value="annuel">
                    <button class="btn btn-primary w-100" @disabled(! $cinetPayConfigure)>Payer avec CinetPay</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">Historique des paiements</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Plan</th><th>Montant</th><th>Statut</th><th>Mode</th></tr>
            </thead>
            <tbody>
                @forelse ($paiements as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($p->plan) }}</td>
                        <td>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if ($p->statut === 'reussi')
                                <span class="badge bg-green-lt">Réussi</span>
                            @elseif ($p->statut === 'en_attente')
                                <span class="badge bg-yellow-lt">En attente</span>
                            @else
                                <span class="badge bg-red-lt">Échoué</span>
                            @endif
                        </td>
                        <td>{{ $p->mode_paiement ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun paiement pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
