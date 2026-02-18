<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValue
{
    public ?string $messaging_product = null;
    public ?EventEntryChangeValueMetadata $metadata = null;
    /** @var EventEntryChangeValueContact[] */
    public array $contacts = [];
    /** @var EventEntryChangeValueMessage[] */
    public ?array $messages = null;
    /** @var EventEntryChangeValueStatus[] */
    public ?array $statuses = null;
    public ?EventEntryChangeValueWabaInfo $waba_info = null;

    public ?string $event = null;
    public ?EventEntryChangeValueAccountEvent $account_offboarded = null;
    public ?EventEntryChangeValueAccountEvent $account_reconnected = null;
    /** @var array<int,array<string,mixed>>|null */
    public ?array $errors = null;

    public function type(): string
    {
        if ($this->messages !== null) {
            return 'messages';
        } elseif ($this->statuses !== null) {
            return 'statuses';
        } elseif ($this->event !== null || $this->account_offboarded !== null || $this->account_reconnected !== null) {
            return 'event';
        } else {
            return 'unknown';
        }
    }

    public function isAccountOffboarded(): bool
    {
        return $this->event === 'account_offboarded' || $this->account_offboarded !== null;
    }

    public function isAccountReconnected(): bool
    {
        return $this->event === 'account_reconnected' || $this->account_reconnected !== null;
    }
}
