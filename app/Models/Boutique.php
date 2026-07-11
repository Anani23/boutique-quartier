<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boutique extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'adresse', 'telephone'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Categorie::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
