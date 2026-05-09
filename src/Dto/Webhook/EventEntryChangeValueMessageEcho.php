<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Echo of an outbound message sent from the WhatsApp Business App on the
 * device paired with this WABA via Cloud API + WA Business App coexistence.
 *
 * Same envelope as {@see EventEntryChangeValueMessage} (text, image, video,
 * audio, document, etc. follow identical sub-DTOs) plus the recipient
 * identifiers Meta only emits on echoes:
 *
 *  - `to`          — recipient phone number (E.164 without `+`).
 *  - `to_user_id`  — BSUID of the recipient when the conversation uses
 *                    Business Solution User IDs instead of plain phone
 *                    numbers (privacy-preserving identifier rolled out by
 *                    Meta for some customers).
 */
class EventEntryChangeValueMessageEcho extends EventEntryChangeValueMessage
{
    public ?string $to = null;
    public ?string $to_user_id = null;
}
