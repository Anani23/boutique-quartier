<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = User::where('boutique_id', $this->boutiqueId())->orderBy('name')->get();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'boutique_id' => $this->boutiqueId(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_VENDEUR,
        ]);

        return back()->with('success', 'Vendeur ajouté.');
    }

    public function destroy(User $utilisateur)
    {
        abort_unless($utilisateur->boutique_id === $this->boutiqueId(), 403);
        abort_if($utilisateur->id === auth()->id(), 403, 'Vous ne pouvez pas vous supprimer vous-même.');
        abort_if($utilisateur->isGerant(), 403, 'Le gérant ne peut pas être supprimé.');

        $utilisateur->delete();

        return back()->with('success', 'Vendeur supprimé.');
    }
}
