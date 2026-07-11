<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::where('boutique_id', $this->boutiqueId())
            ->withCount('produits')
            ->orderBy('nom')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);
        $data['boutique_id'] = $this->boutiqueId();

        Categorie::create($data);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroy(Categorie $categorie)
    {
        abort_unless($categorie->boutique_id === $this->boutiqueId(), 403);
        $categorie->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }
}
