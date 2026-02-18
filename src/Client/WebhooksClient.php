<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use ReflectionException;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidSignatureException;
use Softlivery\WhatsappCloudApiClient\PayloadMapper;
use Softlivery\WhatsappCloudApiClient\PayloadMapperInterface;
use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;

final class WebhooksClient
{
    private WebhookEventHelper $helper;

    public function __construct(string $verifyToken, string $appSecret, PayloadMapperInterface $payloadMapper = new PayloadMapper())
    {
        $this->helper = new WebhookEventHelper($verifyToken, $appSecret, $payloadMapper);
    }

    public function isVerifyTokenValid(string $token): bool
    {
        return $this->helper->isHubVerifyTokenValid($token);
    }

    /**
     * @throws InvalidSignatureException
     * @throws ReflectionException
     */
    public function parseEvent(string $payload, array $headers = []): Event
    {
        return $this->helper->validateAndParse($payload, $headers);
    }
}
