@extends('layouts.app')

@section('title', 'Vendeurs')

@section('content')
<h4 class="mb-4">Vendeurs de la boutique</h4>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h6>Ajouter un vendeur</h6>
                <form method="POST" action="{{ route('utilisateurs.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($utilisateurs as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge bg-secondary">{{ $u->isGerant() ? 'Gérant' : 'Vendeur' }}</span></td>
                                <td class="text-end">
                                    @if (! $u->isGerant())
                                        <form action="{{ route('utilisateurs.destroy', $u) }}" method="POST" onsubmit="return confirm('Supprimer ce vendeur ?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Aucun vendeur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
