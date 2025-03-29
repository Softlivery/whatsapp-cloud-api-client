<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueStatus
{
    public string $biz_opaque_callback_data;
    public EventEntryChangeValueStatusConversation $conversation;
    public string $id;
    public EventEntryChangeValueStatusPricing $pricing;
    public string $recipient_id;
    public string $status;
    public string $timestamp;
}
