<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageContext
{
    public bool $forwarded;
    public bool $frequently_forwarded;
    public string $from;
    public string $id;
    public object $referred_product;
}
