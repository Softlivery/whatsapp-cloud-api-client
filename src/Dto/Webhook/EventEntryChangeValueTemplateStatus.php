<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Payload for message_template_status_update and message_template_quality_update webhook fields.
 *
 * Meta docs: https://developers.facebook.com/docs/whatsapp/business-management-api/webhooks
 *
 * Status update example:
 * {
 *   "event": "APPROVED",
 *   "message_template_id": 123456,
 *   "message_template_name": "my_template",
 *   "message_template_language": "en_US",
 *   "reason": null
 * }
 *
 * Quality update example:
 * {
 *   "message_template_id": 123456,
 *   "message_template_name": "my_template",
 *   "message_template_language": "en_US",
 *   "new_quality_score": "GREEN",
 *   "previous_quality_score": "YELLOW"
 * }
 */
class EventEntryChangeValueTemplateStatus
{
    /** APPROVED, REJECTED, PENDING_DELETION, FLAGGED, PAUSED, DISABLED, etc. */
    public ?string $event = null;

    public ?int $message_template_id = null;
    public ?string $message_template_name = null;
    public ?string $message_template_language = null;

    /** Rejection reason, e.g. INCORRECT_CATEGORY, INVALID_FORMAT */
    public ?string $reason = null;

    /** Category assigned/changed by Meta, e.g. MARKETING, UTILITY, AUTHENTICATION */
    public ?string $new_category = null;
    public ?string $previous_category = null;

    /** Quality score changes */
    public ?string $new_quality_score = null;
    public ?string $previous_quality_score = null;
}
