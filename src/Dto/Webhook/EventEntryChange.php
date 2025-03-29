<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChange
{
    public string $field;
    public EventEntryChangeValue $value;
}
