<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageLocation
{
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?string $name = null;
    public ?string $address = null;
    public ?string $url = null;
}
