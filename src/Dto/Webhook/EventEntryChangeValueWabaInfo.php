<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueWabaInfo
{
    public ?string $waba_id;
    public ?string $owner_business_id;
    public ?string $partner_app_id;
    public ?string $solution_id;
    /** @var string[] */
    public ?array $solution_partner_business_ids;
}
