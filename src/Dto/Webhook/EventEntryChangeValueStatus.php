<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueStatus
{
    public ?string $biz_opaque_callback_data = null;
    public ?EventEntryChangeValueStatusConversation $conversation = null;
    public ?string $id = null;
    public ?EventEntryChangeValueStatusPricing $pricing = null;
    public ?string $recipient_id = null;
    public ?string $status = null;
    public ?string $timestamp = null;
    public ?string $message_status = null;
    public ?bool $is_deleted = null;
    /** @var array<int,array<string,mixed>>|null */
    public ?array $errors = null;
}
