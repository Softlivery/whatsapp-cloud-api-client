<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageOrderProductItem
{
    public string $product_retailer_id;
    public string $quantity;
    public string $item_price;
    public string $currency;
}
