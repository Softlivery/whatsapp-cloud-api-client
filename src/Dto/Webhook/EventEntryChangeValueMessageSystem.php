<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageSystem
{
    public string $body;
    public string $identity;
    public ?string $new_wa_id;
    public ?string $wa_id;
    public string $type;
    public string $customer;
}
