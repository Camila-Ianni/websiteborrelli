<?php

namespace Tests\Unit;

use App\Services\MercadoPagoService;
use Tests\TestCase;

class MercadoPagoServiceTest extends TestCase
{
    protected MercadoPagoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MercadoPagoService();
    }

    public function test_service_can_be_instantiated(): void
    {
        $this->assertInstanceOf(MercadoPagoService::class, $this->service);
    }

    public function test_is_configured_returns_false_without_credentials(): void
    {
        // Without proper env vars, should return false
        config(['services.mercadopago.access_token' => '']);
        config(['services.mercadopago.public_key' => '']);
        
        $service = new MercadoPagoService();
        $this->assertFalse($service->isConfigured());
    }

    public function test_get_public_key_returns_string(): void
    {
        $publicKey = $this->service->getPublicKey();
        $this->assertIsString($publicKey);
    }

    public function test_format_items_structure(): void
    {
        $items = [
            [
                'comic_id' => 1,
                'title' => 'Test Comic',
                'description' => 'A test description',
                'image_url' => 'https://example.com/image.jpg',
                'quantity' => 2,
                'price' => 29.99,
            ],
        ];

        // Use reflection to test protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('formatItems');
        $method->setAccessible(true);

        $formattedItems = $method->invoke($this->service, $items);

        $this->assertCount(1, $formattedItems);
        $this->assertEquals('1', $formattedItems[0]['id']);
        $this->assertEquals('Test Comic', $formattedItems[0]['title']);
        $this->assertEquals(2, $formattedItems[0]['quantity']);
        $this->assertEquals(29.99, $formattedItems[0]['unit_price']);
        $this->assertEquals('ARS', $formattedItems[0]['currency_id']);
    }

    public function test_create_preference_returns_null_without_credentials(): void
    {
        config(['services.mercadopago.access_token' => '']);
        
        $service = new MercadoPagoService();
        
        $result = $service->createPreference(
            [['comic_id' => 1, 'title' => 'Test', 'quantity' => 1, 'price' => 10]],
            ['name' => 'Test', 'email' => 'test@test.com', 'dni' => '123'],
            ['success' => 'http://test.com/success']
        );

        // Without valid credentials, API call will fail
        $this->assertNull($result);
    }

    public function test_verify_webhook_signature_returns_true_without_secret(): void
    {
        config(['services.mercadopago.webhook_secret' => '']);
        
        $service = new MercadoPagoService();
        
        // Without secret configured, should return true (accept all)
        $result = $service->verifyWebhookSignature(
            'ts=123,v1=abc',
            'request-123',
            'data-456'
        );

        $this->assertTrue($result);
    }
}
