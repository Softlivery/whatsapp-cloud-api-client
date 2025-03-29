<?php

namespace Softlivery\WhatsappCloudApiClient\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidSignatureException;
use Softlivery\WhatsappCloudApiClient\PayloadMapperInterface;
use Softlivery\WhatsappCloudApiClient\WebhookEventHandler;
use TypeError;

class WebhookEventHandlerTest extends TestCase
{
    public function testConstructorSuccessfullyInitializesProperties(): void
    {
        $verificationToken = 'test_verification_token';
        $clientSecret = 'test_client_secret';
        $payloadMapper = $this->createMock(PayloadMapperInterface::class);

        $webhookEventHandler = $this->getWebhookEventHandlerInstance($verificationToken, $clientSecret, $payloadMapper);

        $this->assertSame('test_verification_token', $this->getProperty($webhookEventHandler, 'verificationToken'));
        $this->assertSame('test_client_secret', $this->getProperty($webhookEventHandler, 'clientSecret'));
        $this->assertSame($payloadMapper, $this->getProperty($webhookEventHandler, 'payloadMapper'));
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

    private function getProperty(object $object, string $propertyName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        //$property->setAccessible(true);
        return $property->getValue($object);
    }

    public function testConstructorThrowsErrorIfPayloadMapperIsInvalid(): void
    {
        $this->expectException(TypeError::class);

        $verificationToken = 'test_verification_token';
        $clientSecret = 'test_client_secret';

        // Attempting to pass an invalid PayloadMapper
        $payloadMapper = null;

        $this->getWebhookEventHandlerInstance($verificationToken, $clientSecret, $payloadMapper);
    }

    public function testHandleExitsWithVerificationTokenIfValid(): void
    {
        $verificationToken = 'test_verification_token';
        $clientSecret = 'test_client_secret';
        $payloadMapper = $this->createMock(PayloadMapperInterface::class);

        $webhookEventHandler = $this->getWebhookEventHandlerInstance($verificationToken, $clientSecret, $payloadMapper);
        $queryParameters = ['hub_verify_token' => $verificationToken];

        $this->assertSame($verificationToken, $webhookEventHandler->handle('', $queryParameters));
    }

    public function testHandleParsesValidPayload(): void
    {
        $verificationToken = 'test_verification_token';
        $clientSecret = 'test_client_secret';
        $payloadMapper = $this->createMock(PayloadMapperInterface::class);
        $payload = json_encode(['object' => 'page', 'entry' => []]);

        $eventMock = $this->createMock(Event::class);
        $payloadMapper
            ->expects($this->once())
            ->method('map')
            ->with(json_decode($payload, true), Event::class)
            ->willReturn($eventMock);

        $webhookEventHandler = $this->getWebhookEventHandlerInstance($verificationToken, $clientSecret, $payloadMapper);
        $response = $webhookEventHandler->handle($payload, [], ['HTTP_X_HUB_SIGNATURE_256' => 'cdd4201447252a94e91fc9344df28e9716860392fff82c13e7ea3fde99b94929']);

        $this->assertSame($eventMock, $response);
    }

    public function testHandleThrowsExceptionForInvalidSignature(): void
    {
        $verificationToken = 'test_verification_token';
        $clientSecret = 'test_client_secret';
        $payloadMapper = $this->createMock(PayloadMapperInterface::class);
        $payload = json_encode(['object' => 'page', 'entry' => []]);

        $_SERVER['HTTP_X_HUB_SIGNATURE_256'] = 'invalid_signature';

        $webhookEventHandler = $this->getWebhookEventHandlerInstance($verificationToken, $clientSecret, $payloadMapper);

        $this->expectException(InvalidSignatureException::class);

        $webhookEventHandler->handle($payload);
    }
}
