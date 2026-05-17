<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Contact entry delivered by the `smb_app_state_sync` webhook. Meta sends
 * the full SMB app contact book once after the coexistence app is paired,
 * then incremental updates when the user adds, edits, or removes contacts
 * on the device.
 *
 *  - `full_name`     — display name from the contact book entry.
 *  - `first_name`    — first name when the contact card stores it
 *                      separately. Often equal to `full_name`.
 *  - `phone_number`  — E.164 digits (no `+`). May be empty when the
 *                      contact is a usernames-only user.
 *  - `user_id`       — Business Solution User ID (BSUID). Required.
 */
class EventEntryChangeValueStateSyncContact
{
    public ?string $full_name = null;
    public ?string $first_name = null;
    public ?string $phone_number = null;
    public ?string $user_id = null;
}
