<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * One entry inside an `smb_app_state_sync` webhook payload. Each entry
 * represents a state change for a single SMB-app resource — currently
 * only `type = "contact"` is delivered, but the field is open to future
 * resource types (labels, lists, automated replies, …).
 *
 *  - `type`     — currently always `"contact"`.
 *  - `contact`  — populated when `type === 'contact'`.
 *  - `action`   — `"add"`, `"update"`, or `"remove"`.
 *  - `metadata` — timestamp + schema version of this entry.
 */
class EventEntryChangeValueStateSyncEntry
{
    public ?string $type = null;
    public ?EventEntryChangeValueStateSyncContact $contact = null;
    public ?string $action = null;
    public ?EventEntryChangeValueStateSyncMetadata $metadata = null;
}
