<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_REUSSI = 'reussi';
    const STATUT_ECHOUE = 'echoue';
    const STATUT_ANNULE = 'annule';

    const PLAN_MENSUEL = 'mensuel';
    const PLAN_ANNUEL = 'annuel';

    const PRIX = [
        self::PLAN_MENSUEL => 2000,
        self::PLAN_ANNUEL => 20000,
    ];

    protected $fillable = [
        'boutique_id',
        'plan',
        'montant',
        'devise',
        'transaction_id',
        'statut',
        'mode_paiement',
        'payment_url',
    ];

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }
}
