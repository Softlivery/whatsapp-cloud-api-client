<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntry
{
    public string $id;
    /** @var EventEntryChange[] */
    public array $changes;
}
