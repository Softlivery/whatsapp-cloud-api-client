<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValue
{
    public string $messaging_product;
    public EventEntryChangeValueMetadata $metadata;
    /** @var EventEntryChangeValueContact[] */
    public array $contacts;
    /** @var EventEntryChangeValueMessage[] */
    public ?array $messages;
    /** @var EventEntryChangeValueStatus[] */
    public ?array $statuses;

    public function type(): string
    {
        if (isset($this->messages)) {
            return 'messages';
        } elseif (isset($this->statuses)) {
            return 'statuses';
        } else {
            return 'unknown';
        }
    }
}
