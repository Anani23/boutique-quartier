<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class CinetPayService
{
    private string $apiKey;
    private string $siteId;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.cinetpay.api_key');
        $this->siteId = (string) config('services.cinetpay.site_id');
        $this->baseUrl = (string) config('services.cinetpay.base_url');
    }

    public function estConfigure(): bool
    {
        return $this->apiKey !== '' && $this->siteId !== '';
    }

    /**
     * Initie un paiement CinetPay et renvoie ['payment_token' => ..., 'payment_url' => ...].
     */
    public function initierPaiement(array $donnees): array
    {
        $reponse = Http::asJson()->post($this->baseUrl.'/payment', [
            'apikey' => $this->apiKey,
            'site_id' => $this->siteId,
            'transaction_id' => $donnees['transaction_id'],
            'amount' => (int) $donnees['montant'],
            'currency' => 'XOF',
            'description' => $donnees['description'],
            'customer_name' => $donnees['nom'],
            'customer_surname' => $donnees['prenom'] ?? '.',
            'customer_phone_number' => $donnees['telephone'] ?? '00000000',
            'customer_country' => 'TG',
            'notify_url' => config('services.cinetpay.notify_url'),
            'return_url' => $donnees['return_url'] ?? config('services.cinetpay.return_url'),
            'channels' => 'ALL',
            'lang' => 'fr',
        ])->json();

        if (($reponse['code'] ?? null) != 201) {
            throw new RuntimeException($reponse['description'] ?? 'Échec de l\'initialisation du paiement CinetPay.');
        }

        return [
            'payment_token' => $reponse['data']['payment_token'] ?? null,
            'payment_url' => $reponse['data']['payment_url'] ?? null,
        ];
    }

    /**
     * Interroge CinetPay pour connaître le statut réel d'une transaction.
     * Renvoie ['status' => 'ACCEPTED'|'REFUSED'|'CANCELLED'|'PENDING', 'payment_method' => ...].
     */
    public function verifierStatut(string $transactionId): array
    {
        $reponse = Http::asJson()->post($this->baseUrl.'/payment/check', [
            'apikey' => $this->apiKey,
            'site_id' => $this->siteId,
            'transaction_id' => $transactionId,
        ])->json();

        return [
            'status' => $reponse['data']['status'] ?? 'PENDING',
            'payment_method' => $reponse['data']['payment_method'] ?? null,
        ];
    }
}
