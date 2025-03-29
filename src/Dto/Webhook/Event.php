<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class Event
{
    public string $object;
    /** @var EventEntry[] */
    public array $entry;
}
