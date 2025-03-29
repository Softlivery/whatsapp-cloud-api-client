<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueStatusConversation
{
    public string $id;
    public EventEntryChangeValueStatusConversationOrigin $origin;
    public string $expiration_timestamp;
}
