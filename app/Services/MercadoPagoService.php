<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoService
{
    public function createPreference(
        Order $order,
        $items,
        string $successUrl,
        string $failureUrl,
        string $pendingUrl,
        ?string $notificationUrl = null
    ): array
    {
        $token = config('services.mercadopago.token');
        if (! $token) {
            throw new RuntimeException('MercadoPago credentials not configured.');
        }

        $payloadItems = collect($items)->map(function ($item) {
            $product = $item->product ?? null;

            return [
                'title' => $product?->title ?? 'Producto',
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'currency_id' => 'ARS',
            ];
        })->values()->all();

        $payload = [
            'items' => $payloadItems,
            'external_reference' => $order->order_number,
            'back_urls' => [
                'success' => $successUrl,
                'failure' => $failureUrl,
                'pending' => $pendingUrl,
            ],
            'auto_return' => 'approved',
        ];

        if ($notificationUrl) {
            $payload['notification_url'] = $notificationUrl;
        }

        $client = Http::withToken($token);
        if ($integratorId = config('services.mercadopago.integrator_id')) {
            $client = $client->withHeaders(['x-integrator-id' => $integratorId]);
        }

        $response = $client->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('MercadoPago error: ' . $response->body());
        }

        return $response->json();
    }

    public function getPayment(string $paymentId): array
    {
        $token = config('services.mercadopago.token');
        if (! $token) {
            throw new RuntimeException('MercadoPago credentials not configured.');
        }

        $client = Http::withToken($token);
        if ($integratorId = config('services.mercadopago.integrator_id')) {
            $client = $client->withHeaders(['x-integrator-id' => $integratorId]);
        }

        $response = $client->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (! $response->successful()) {
            throw new RuntimeException('MercadoPago payment fetch error: ' . $response->body());
        }

        return $response->json();
    }
}
