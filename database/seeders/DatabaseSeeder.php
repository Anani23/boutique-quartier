<?php

namespace Database\Seeders;

use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\LigneVente;
use App\Models\Produit;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $boutique = Boutique::create([
            'nom' => 'Épicerie du Coin',
            'adresse' => '12 rue des Manguiers, Quartier Nord',
            'telephone' => '01 23 45 67 89',
        ]);

        $gerant = User::create([
            'boutique_id' => $boutique->id,
            'name' => 'Awa Diallo',
            'email' => 'gerant@boutique.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_GERANT,
        ]);

        User::create([
            'boutique_id' => $boutique->id,
            'name' => 'Moussa Kane',
            'email' => 'vendeur@boutique.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_VENDEUR,
        ]);

        $categories = collect(['Boissons', 'Épicerie', 'Hygiène'])->map(
            fn ($nom) => Categorie::create(['boutique_id' => $boutique->id, 'nom' => $nom])
        );

        $produits = [
            ['nom' => 'Eau minérale 1.5L', 'cat' => 'Boissons', 'achat' => 300, 'vente' => 500, 'stock' => 40, 'seuil' => 10],
            ['nom' => 'Jus de bissap 1L', 'cat' => 'Boissons', 'achat' => 600, 'vente' => 1000, 'stock' => 20, 'seuil' => 5],
            ['nom' => 'Riz parfumé 5kg', 'cat' => 'Épicerie', 'achat' => 3500, 'vente' => 4500, 'stock' => 15, 'seuil' => 5],
            ['nom' => 'Huile végétale 1L', 'cat' => 'Épicerie', 'achat' => 1200, 'vente' => 1600, 'stock' => 3, 'seuil' => 5],
            ['nom' => 'Sucre 1kg', 'cat' => 'Épicerie', 'achat' => 500, 'vente' => 750, 'stock' => 25, 'seuil' => 8],
            ['nom' => 'Savon de toilette', 'cat' => 'Hygiène', 'achat' => 250, 'vente' => 400, 'stock' => 0, 'seuil' => 10],
        ];

        $produitsCrees = collect($produits)->map(function ($p) use ($boutique, $categories) {
            return Produit::create([
                'boutique_id' => $boutique->id,
                'categorie_id' => $categories->firstWhere('nom', $p['cat'])->id,
                'nom' => $p['nom'],
                'prix_achat' => $p['achat'],
                'prix_vente' => $p['vente'],
                'quantite_stock' => $p['stock'],
                'seuil_alerte' => $p['seuil'],
            ]);
        });

        foreach (range(1, 5) as $i) {
            $vente = Vente::create([
                'boutique_id' => $boutique->id,
                'user_id' => $gerant->id,
                'numero_recu' => 'R-DEMO-'.$i.'-'.Str::upper(Str::random(4)),
                'total' => 0,
                'created_at' => now()->subDays(5 - $i),
                'updated_at' => now()->subDays(5 - $i),
            ]);

            $total = 0;
            foreach ($produitsCrees->random(2) as $produit) {
                $qte = rand(1, 3);
                $sousTotal = $produit->prix_vente * $qte;
                LigneVente::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'nom_produit' => $produit->nom,
                    'quantite' => $qte,
                    'prix_unitaire' => $produit->prix_vente,
                    'sous_total' => $sousTotal,
                ]);
                $total += $sousTotal;
            }
            $vente->update(['total' => $total]);
        }
    }
}
