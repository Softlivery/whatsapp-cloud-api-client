<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Identifiers of the WhatsApp user this history thread belongs to.
 *
 *  - `wa_id`           — phone number (E.164 without `+`). May be omitted
 *                        when the user enabled usernames + opted out of
 *                        sharing their phone number.
 *  - `user_id`         — Business Solution User ID (BSUID) — privacy-
 *                        preserving identifier introduced for coexistence.
 *  - `parent_user_id`  — parent BSUID, present only if the App enabled
 *                        parent BSUIDs before the sync request.
 *  - `username`        — set when the user opted into the usernames feature.
 */
class EventEntryChangeValueHistoryThreadContext
{
    public ?string $wa_id = null;
    public ?string $user_id = null;
    public ?string $parent_user_id = null;
    public ?string $username = null;
}
