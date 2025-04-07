<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Events;

use Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event;

class WebhookEvent
{
    private Event $payload;

    public function __construct(Event $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return Event
     */
    public function getPayload(): Event
    {
        return $this->payload;
    }
}
