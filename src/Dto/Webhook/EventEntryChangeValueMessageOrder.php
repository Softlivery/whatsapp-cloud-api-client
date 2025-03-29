<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageOrder
{
    public string $catalog_id;
    public string $text;
    /** @var EventEntryChangeValueMessageOrderProductItem[] */
    public array $product_items;
}
