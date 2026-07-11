@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<h4 class="mb-4">Catégories</h4>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h6>Ajouter une catégorie</h6>
                <form method="POST" action="{{ route('categories.store') }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="nom" class="form-control" placeholder="Nom de la catégorie" required>
                    <button class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Nom</th><th>Produits</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($categories as $cat)
                            <tr>
                                <td>{{ $cat->nom }}</td>
                                <td>{{ $cat->produits_count }}</td>
                                <td class="text-end">
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Aucune catégorie.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
