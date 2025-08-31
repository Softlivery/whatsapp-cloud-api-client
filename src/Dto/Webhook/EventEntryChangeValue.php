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
    public ?EventEntryChangeValueWabaInfo $waba_info;

    public ?string $event;

    public function type(): string
    {
        if (isset($this->messages)) {
            return 'messages';
        } elseif (isset($this->statuses)) {
            return 'statuses';
        } elseif (isset($this->event)) {
            return 'event';
        } else {
            return 'unknown';
        }
    }
}
