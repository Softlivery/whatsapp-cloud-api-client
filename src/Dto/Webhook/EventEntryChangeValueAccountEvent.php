<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueAccountEvent
{
    public ?string $waba_id = null;
    public ?string $phone_number_id = null;
    public ?string $reason = null;
    public ?string $timestamp = null;
}
