<?php

namespace Softlivery\WhatsappCloudApiClient\Tests\E2E;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidSignatureException;
use Softlivery\WhatsappCloudApiClient\PayloadMapper;
use Softlivery\WhatsappCloudApiClient\PayloadMapperInterface;
use Softlivery\WhatsappCloudApiClient\WebhookEventHandler;

class WebhookEventHandlerE2ETest extends TestCase
{
    private string $verificationToken;
    private string $clientSecret;
    private PayloadMapperInterface $payloadMapper;
    private WebhookEventHandler $handler;

    public function testHandleParsesMessagePayload(): void
    {
        // Example payload passed
        $payload = file_get_contents(__DIR__ . '/examples/WebhookMessageEvent.json');

        // Simulate handling the payload (with matching signature)
        $validSignature = hash_hmac('sha256', $payload, $this->clientSecret);

        $result = $this->handler->handle($payload, [], [
            'HTTP_X_HUB_SIGNATURE_256' => "sha256=$validSignature"
        ]);

        // Assert that the result is the expected mapped Event object
        $this->assertEquals('1394258531579758', $result->entry[0]->changes[0]->value->messages[0]->image->id);
    }

    public function testHandleParsesStatusPayload(): void
    {
        // Example payload passed
        $payload = file_get_contents(__DIR__ . '/examples/WebhookStatusEvent.json');

        // Simulate handling the payload (with matching signature)
        $validSignature = hash_hmac('sha256', $payload, $this->clientSecret);

        $result = $this->handler->handle($payload, [], [
            'HTTP_X_HUB_SIGNATURE_256' => "sha256=$validSignature"
        ]);

        // Assert that the result is the expected mapped Event object
        $this->assertEquals('sent', $result->entry[0]->changes[0]->value->statuses[0]->status);
    }

    public function testHandleThrowsExceptionForInvalidSignature(): void
    {
        // Example payload
        $payload = file_get_contents(__DIR__ . '/examples/WebhookStatusEvent.json');

        // Simulate an incorrect signature
        $invalidSignature = "sha256=this_is_an_invalid_signature";

        $this->expectException(InvalidSignatureException::class);

        // Attempt to handle the payload with an invalid signature
        $this->handler->handle($payload, [], [
            'HTTP_X_HUB_SIGNATURE_256' => $invalidSignature
        ]);
    }

    protected function setUp(): void
    {
        $this->verificationToken = 'test_verification_token';
        $this->clientSecret = 'test_client_secret';

        // Mock PayloadMapper behavior for mapping the payload
        //$this->payloadMapper = $this->createMock(PayloadMapper::class);
        $this->payloadMapper = new PayloadMapper();
        $this->handler = $this->getWebhookEventHandlerInstance($this->verificationToken, $this->clientSecret, $this->payloadMapper);
    }

    private function getWebhookEventHandlerInstance(string $verificationToken, string $clientSecret, $payloadMapper): WebhookEventHandler
    {
        return new class($verificationToken, $clientSecret, $payloadMapper) extends WebhookEventHandler {
            public function __construct(string $verificationToken, string $clientSecret, $payloadMapper)
            {
                parent::__construct($verificationToken, $clientSecret, $payloadMapper);
            }
        };
    }
}
