<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'nom',
        'description',
        'image_url',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'seuil_alerte',
    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Produit $produit) {
            if (empty($produit->image_url)) {
                $produit->image_url = 'https://picsum.photos/seed/'.urlencode(Str::slug($produit->nom ?: Str::random(8))).'/400/300';
            }
        });
    }

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function getEnRuptureAttribute(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }

    public function getCodeQrAttribute(): string
    {
        return 'PROD-'.$this->id;
    }
}
