<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $produits = Produit::with('categorie')
            ->where('boutique_id', $this->boutiqueId())
            ->when($request->filled('q'), fn ($q) => $q->where('nom', 'like', '%'.$request->q.'%'))
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('produits.index', compact('produits'));
    }

    public function create()
    {
        $categories = Categorie::where('boutique_id', $this->boutiqueId())->orderBy('nom')->get();

        return view('produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['boutique_id'] = $this->boutiqueId();

        Produit::create($data);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté.');
    }

    public function edit(Produit $produit)
    {
        $this->authorizeBoutique($produit);
        $categories = Categorie::where('boutique_id', $this->boutiqueId())->orderBy('nom')->get();

        return view('produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $this->authorizeBoutique($produit);
        $produit->update($this->validateData($request));

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        $this->authorizeBoutique($produit);
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'categorie_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('boutique_id', $this->boutiqueId()),
            ],
            'prix_achat' => ['required', 'numeric', 'min:0'],
            'prix_vente' => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'seuil_alerte' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function authorizeBoutique(Produit $produit): void
    {
        abort_unless($produit->boutique_id === $this->boutiqueId(), 403);
    }
}
