<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boutique extends Model
{
    use HasFactory;

    const STATUT_ESSAI = 'essai';
    const STATUT_ACTIF = 'actif';
    const STATUT_EXPIRE = 'expire';

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'plan',
        'abonnement_statut',
        'essai_expire_le',
        'abonnement_expire_le',
    ];

    protected $casts = [
        'essai_expire_le' => 'datetime',
        'abonnement_expire_le' => 'datetime',
    ];

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

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function accesActif(): bool
    {
        if ($this->abonnement_statut === self::STATUT_ACTIF) {
            return $this->abonnement_expire_le && $this->abonnement_expire_le->isFuture();
        }

        if ($this->abonnement_statut === self::STATUT_ESSAI) {
            return $this->essai_expire_le && $this->essai_expire_le->isFuture();
        }

        return false;
    }

    public function joursEssaiRestants(): int
    {
        if (! $this->essai_expire_le) {
            return 0;
        }

        return max(0, (int) now()->diffInDays($this->essai_expire_le, false));
    }
}
