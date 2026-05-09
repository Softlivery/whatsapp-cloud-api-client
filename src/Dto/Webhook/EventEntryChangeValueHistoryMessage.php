<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Message inside a `history` thread. Same envelope as
 * {@see EventEntryChangeValueMessage} (text, image, video, audio, document
 * and their sub-DTOs are reused) plus history-specific identifiers and the
 * historic delivery status.
 *
 *  - `from_user_id`         — BSUID of the message sender. Either the
 *                              business or the WhatsApp user, depending on
 *                              direction.
 *  - `from_parent_user_id`  — parent BSUID, when the App enabled parent
 *                              BSUIDs before the sync request.
 *  - `history_context.status` — final status (`sent`/`delivered`/`read`/
 *                              `failed`) at the time of sync.
 */
class EventEntryChangeValueHistoryMessage extends EventEntryChangeValueMessage
{
    public ?string $from_user_id = null;
    public ?string $from_parent_user_id = null;
    public ?EventEntryChangeValueHistoryMessageContext $history_context = null;
}
