<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    public function createOrder(
        Order $order,
        string $returnUrl,
        string $cancelUrl,
        ?string $currency = null
    ): array
    {
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $order->order_number,
                    'custom_id' => $order->order_number,
                    'amount' => [
                        'currency_code' => $currency ?? config('services.paypal.currency', 'USD'),
                        'value' => number_format((float) $order->total, 2, '.', ''),
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => config('app.name', 'Borrelli'),
                'landing_page' => 'NO_PREFERENCE',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];

        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl() . '/v2/checkout/orders', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('PayPal order creation error: ' . $response->body());
        }

        $data = $response->json();
        $approvalLink = collect($data['links'] ?? [])->firstWhere('rel', 'approve');
        $approvalUrl = is_array($approvalLink) ? ($approvalLink['href'] ?? null) : null;

        if (! $approvalUrl) {
            throw new RuntimeException('PayPal approval URL was not returned.');
        }

        if (empty($data['id'])) {
            throw new RuntimeException('PayPal order ID was not returned.');
        }

        return [
            'id' => $data['id'],
            'status' => $data['status'] ?? null,
            'approval_url' => $approvalUrl,
            'order_reference' => $order->order_number,
            'raw' => $data,
        ];
    }

    public function captureOrder(string $paypalOrderId): array
    {
        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl() . "/v2/checkout/orders/{$paypalOrderId}/capture");

        if (! $response->successful()) {
            throw new RuntimeException('PayPal capture error: ' . $response->body());
        }

        return $response->json();
    }

    private function accessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret') ?: env('PAYPAL_SECRET');

        if (! $clientId || ! $secret) {
            throw new RuntimeException('PayPal credentials not configured.');
        }

        $response = Http::asForm()
            ->withBasicAuth((string) $clientId, (string) $secret)
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('PayPal authentication error: ' . $response->body());
        }

        $token = $response->json('access_token');
        if (! is_string($token) || $token === '') {
            throw new RuntimeException('PayPal access token was not returned.');
        }

        return $token;
    }

    private function baseUrl(): string
    {
        return config('services.paypal.mode', 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }
}
