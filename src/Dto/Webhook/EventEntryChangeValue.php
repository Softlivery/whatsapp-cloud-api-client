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
    /** @var EventEntryChangeValueMessageEcho[] */
    public ?array $message_echoes = null;
    public ?EventEntryChangeValueWabaInfo $waba_info = null;

    public ?string $event = null;
    public ?EventEntryChangeValueAccountEvent $account_offboarded = null;
    public ?EventEntryChangeValueAccountEvent $account_reconnected = null;
    /** @var array<int,array<string,mixed>>|null */
    public ?array $errors = null;

    /** Populated for message_template_status_update and message_template_quality_update fields. */
    public ?int $message_template_id = null;
    public ?string $message_template_name = null;
    public ?string $message_template_language = null;
    public ?string $reason = null;
    public ?string $new_category = null;
    public ?string $previous_category = null;
    public ?string $new_quality_score = null;
    public ?string $previous_quality_score = null;

    public function type(): string
    {
        if ($this->messages !== null) {
            return 'messages';
        } elseif ($this->statuses !== null) {
            return 'statuses';
        } elseif ($this->message_echoes !== null) {
            return 'smb_message_echoes';
        } elseif ($this->message_template_id !== null || $this->message_template_name !== null) {
            return 'message_template_status_update';
        } elseif ($this->event !== null || $this->account_offboarded !== null || $this->account_reconnected !== null) {
            return 'event';
        } else {
            return 'unknown';
        }
    }

    public function toTemplateStatus(): EventEntryChangeValueTemplateStatus
    {
        $dto = new EventEntryChangeValueTemplateStatus();
        $dto->event                    = $this->event;
        $dto->message_template_id      = $this->message_template_id;
        $dto->message_template_name    = $this->message_template_name;
        $dto->message_template_language = $this->message_template_language;
        $dto->reason                   = $this->reason;
        $dto->new_category             = $this->new_category;
        $dto->previous_category        = $this->previous_category;
        $dto->new_quality_score        = $this->new_quality_score;
        $dto->previous_quality_score   = $this->previous_quality_score;
        return $dto;
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
