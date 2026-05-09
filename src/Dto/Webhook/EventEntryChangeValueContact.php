<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueContact
{
    /**
     * Optional. Coexistence echo payloads omit `profile` because the
     * outbound is on behalf of the business, not a customer profile.
     */
    public ?EventEntryChangeValueContactProfile $profile = null;
    public string $wa_id;
    /**
     * Business Solution User ID. Populated when Meta delivers the
     * privacy-preserving identifier instead of (or alongside) `wa_id`.
     */
    public ?string $user_id = null;
}
