<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueUserIdUpdate
{
    public ?string $wa_id = null;
    public ?string $detail = null;
    public ?EventEntryChangeValueUserIdUpdateChange $user_id = null;
    public ?EventEntryChangeValueUserIdUpdateChange $parent_user_id = null;
    public ?string $timestamp = null;
}
