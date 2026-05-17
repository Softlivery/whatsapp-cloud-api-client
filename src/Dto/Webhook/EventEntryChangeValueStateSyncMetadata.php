<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Metadata attached to each `smb_app_state_sync` entry.
 *
 *  - `timestamp` — millisecond Unix timestamp as a string (Meta delivers
 *                  it without parsing).
 *  - `version`   — schema version of the entry; current production payloads
 *                  use `1`.
 */
class EventEntryChangeValueStateSyncMetadata
{
    public ?string $timestamp = null;
    public ?int $version = null;
}
