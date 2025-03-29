<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueStatusPricing
{
    public bool $billable;
    public string $category;
    public string $pricing_model;
}
