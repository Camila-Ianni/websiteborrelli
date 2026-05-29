<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected string $accessToken;
    protected string $publicKey;
    protected string $baseUrl = 'https://api.mercadopago.com';

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token');
        $this->publicKey = config('services.mercadopago.public_key');
    }

    /**
     * Crear una preferencia de pago
     */
    public function createPreference(array $items, array $payer, array $backUrls, string $externalReference = null): ?array
    {
        $preference = [
            'items' => $this->formatItems($items),
            'payer' => [
                'name' => $payer['name'] ?? '',
                'email' => $payer['email'] ?? '',
                'identification' => [
                    'type' => 'DNI',
                    'number' => $payer['dni'] ?? '',
                ],
            ],
            'back_urls' => [
                'success' => $backUrls['success'] ?? url('/checkout/success'),
                'failure' => $backUrls['failure'] ?? url('/checkout/failure'),
                'pending' => $backUrls['pending'] ?? url('/checkout/pending'),
            ],
            'auto_return' => 'approved',
            'external_reference' => $externalReference,
            'notification_url' => url('/webhooks/mercadopago'),
            'statement_descriptor' => 'EL TUNEL DEL COMIC',
            'expires' => false,
        ];

        try {
            $response = Http::withToken($this->accessToken)
                ->post("{$this->baseUrl}/checkout/preferences", $preference);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('MercadoPago preference error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('MercadoPago exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Formatear items para la preferencia
     */
    protected function formatItems(array $cartItems): array
    {
        return array_map(function ($item) {
            return [
                'id' => (string) $item['comic_id'],
                'title' => $item['title'],
                'description' => $item['description'] ?? 'Comic',
                'picture_url' => $item['image_url'] ?? '',
                'category_id' => 'entertainment',
                'quantity' => (int) $item['quantity'],
                'currency_id' => 'ARS',
                'unit_price' => (float) $item['price'],
            ];
        }, $cartItems);
    }

    /**
     * Obtener información de un pago
     */
    public function getPayment(string $paymentId): ?array
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/v1/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('MercadoPago get payment error', [
                'payment_id' => $paymentId,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('MercadoPago get payment exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verificar webhook signature
     */
    public function verifyWebhookSignature(string $xSignature, string $xRequestId, string $dataId): bool
    {
        $secret = config('services.mercadopago.webhook_secret');
        
        if (empty($secret)) {
            return true; // Si no hay secret configurado, aceptar todos
        }

        // Parsear x-signature
        $parts = [];
        foreach (explode(',', $xSignature) as $part) {
            [$key, $value] = explode('=', $part, 2);
            $parts[trim($key)] = trim($value);
        }

        $ts = $parts['ts'] ?? '';
        $v1 = $parts['v1'] ?? '';

        // Generar firma
        $manifest = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";
        $expectedSignature = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($expectedSignature, $v1);
    }

    /**
     * Obtener la Public Key para el frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Verificar si MercadoPago está configurado
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->publicKey);
    }
}
