<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use ReflectionException;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidSignatureException;

abstract class WebhookEventHandler
{
    protected const HUB_VERIFY_TOKEN_KEY = 'hub_verify_token';
    protected const SIGNATURE_PREFIX = 'sha256=';
    const HTTP_X_HUB_SIGNATURE_256 = 'HTTP_X_HUB_SIGNATURE_256';
    protected string $verificationToken;
    protected string $clientSecret;
    protected PayloadMapperInterface $payloadMapper;

    /**
     * @param string $verificationToken
     * @param string $clientSecret
     * @param PayloadMapperInterface $payloadMapper
     */
    protected function __construct(string $verificationToken, string $clientSecret, PayloadMapperInterface $payloadMapper = new PayloadMapper())
    {
        $this->verificationToken = $verificationToken;
        $this->clientSecret = $clientSecret;
        $this->payloadMapper = $payloadMapper;
    }

    /**
     * @param string $payload
     * @param array $queryParameters
     * @param array $headers
     * @return Event|string
     * @throws InvalidSignatureException
     * @throws ReflectionException
     */
    public function handle(string $payload, array $queryParameters = [], array $headers = []): Event|string
    {
        if (array_key_exists(static::HUB_VERIFY_TOKEN_KEY, $queryParameters) &&
            $this->isHubVerifyTokenValid($queryParameters)) {
            return $this->verificationToken;
        }

        $this->validateSignature($payload, $headers);

        return $this->parse($payload);
    }

    /**
     * @param array $queryParameters
     * @return bool
     */
    protected function isHubVerifyTokenValid(array $queryParameters): bool
    {
        return $queryParameters[static::HUB_VERIFY_TOKEN_KEY] === $this->verificationToken;
    }

    /**
     * @param string $payload
     * @param array $headers
     * @return void
     * @throws InvalidSignatureException
     */
    protected function validateSignature(string $payload, array $headers): void
    {
        $computedSignature = $this->computeSignature($payload);
        $headerSignature = $this->getSignatureFromHeader($headers);

        if (!$this->isSignatureValid($computedSignature, $headerSignature)) {
            throw new InvalidSignatureException();
        }
    }

    /**
     * @param string $payload
     * @return string
     */
    protected function computeSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->clientSecret);
    }

    /**
     * @param array $headers
     * @return string
     */
    protected function getSignatureFromHeader(array $headers): string
    {
        return str_replace(static::SIGNATURE_PREFIX, '', $headers[static::HTTP_X_HUB_SIGNATURE_256] ?? '');
    }

    /**
     * @param string $computedSignature
     * @param string $headerSignature
     * @return bool
     */
    protected function isSignatureValid(string $computedSignature, string $headerSignature): bool
    {
        return hash_equals($computedSignature, $headerSignature);
    }

    /**
     * @param string $payload
     * @return Event
     * @throws ReflectionException
     */
    protected function parse(string $payload): Event
    {
        $decodedPayload = json_decode($payload, true);
        return $this->payloadMapper->map($decodedPayload, Event::class);
    }
}
