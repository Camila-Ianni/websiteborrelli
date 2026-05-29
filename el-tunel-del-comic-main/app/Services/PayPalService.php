<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.mode') === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }

    protected function getAccessToken(): ?string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('PayPal token error', ['response' => $response->json()]);
        return null;
    }

    public function createOrder(array $items, float $total, string $orderNumber, string $returnUrl, string $cancelUrl): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $orderNumber,
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($total, 2, '.', ''),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'USD',
                                    'value' => number_format($total, 2, '.', '')
                                ]
                            ]
                        ],
                        'items' => $this->formatItems($items)
                    ]
                ],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => 'El Túnel del Cómic',
                    'shipping_preference' => 'NO_SHIPPING'
                ]
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('PayPal order creation error', ['response' => $response->json()]);
        return null;
    }

    public function captureOrder(string $orderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('PayPal capture error', ['response' => $response->json()]);
        return null;
    }

    public function getOrder(string $orderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/v2/checkout/orders/{$orderId}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function verifyWebhook(array $headers, string $payload, string $webhookId): bool
    {
        $token = $this->getAccessToken();
        if (!$token) return false;

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v1/notifications/verify-webhook-signature", [
                'auth_algo' => $headers['paypal-auth-algo'] ?? '',
                'cert_url' => $headers['paypal-cert-url'] ?? '',
                'transmission_id' => $headers['paypal-transmission-id'] ?? '',
                'transmission_sig' => $headers['paypal-transmission-sig'] ?? '',
                'transmission_time' => $headers['paypal-transmission-time'] ?? '',
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($payload, true)
            ]);

        if ($response->successful()) {
            return $response->json()['verification_status'] === 'SUCCESS';
        }

        return false;
    }

    protected function formatItems(array $items): array
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'],
                'quantity' => (string) $item['quantity'],
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($item['price'], 2, '.', '')
                ]
            ];
        }, $items);
    }
}
