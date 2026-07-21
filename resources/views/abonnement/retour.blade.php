@extends('layouts.app')

@section('title', 'Résultat du paiement')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center py-5">
                @if ($paiement->statut === 'reussi')
                    <i class="ti ti-circle-check text-green" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Paiement réussi</h2>
                    <p class="text-secondary">Votre abonnement {{ $paiement->plan }} est maintenant actif. Merci !</p>
                @elseif ($paiement->statut === 'en_attente')
                    <i class="ti ti-clock text-yellow" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Paiement en cours de traitement</h2>
                    <p class="text-secondary">Nous n'avons pas encore reçu la confirmation finale. Rechargez cette page dans une minute.</p>
                @else
                    <i class="ti ti-circle-x text-red" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Paiement échoué</h2>
                    <p class="text-secondary">Le paiement n'a pas abouti. Vous pouvez réessayer depuis la page abonnement.</p>
                @endif
                <a href="{{ route('abonnement.index') }}" class="btn btn-primary mt-3">Retour à l'abonnement</a>
            </div>
        </div>
    </div>
</div>
@endsection
