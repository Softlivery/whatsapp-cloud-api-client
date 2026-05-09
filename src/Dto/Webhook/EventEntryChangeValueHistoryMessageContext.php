<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Per-message metadata Meta only emits inside `history` payloads.
 *
 *  - `status` — final status of the message at the time of sync
 *               (`sent`, `delivered`, `read`, `failed`).
 */
class EventEntryChangeValueHistoryMessageContext
{
    public ?string $status = null;
}
