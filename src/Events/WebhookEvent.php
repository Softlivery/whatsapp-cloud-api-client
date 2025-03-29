<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Events;

use Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event;

class WebhookEvent
{
    private Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }
}
