<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueContact
{
    public EventEntryChangeValueContactProfile $profile;
    public string $wa_id;
}
