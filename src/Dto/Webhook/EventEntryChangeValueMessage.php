<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessage
{
    public ?string $from = null;
    public ?string $id = null;
    public ?string $timestamp = null;
    public ?string $type = null;
    public ?EventEntryChangeValueMessageAudio $audio = null;
    public ?EventEntryChangeValueMessageButton $button = null;
    public ?EventEntryChangeValueMessageContext $context = null;
    public ?EventEntryChangeValueMessageDocument $document = null;
    public ?EventEntryChangeValueMessageIdentity $identity = null;
    public ?EventEntryChangeValueMessageImage $image = null;
    public ?EventEntryChangeValueMessageInteractive $interactive = null;
    public ?EventEntryChangeValueMessageOrder $order = null;
    public ?EventEntryChangeValueMessageReferral $referral = null;
    public ?EventEntryChangeValueMessageSticker $sticker = null;
    public ?EventEntryChangeValueMessageSystem $system = null;
    public ?EventEntryChangeValueMessageText $text = null;
    public ?EventEntryChangeValueMessageVideo $video = null;
    public ?EventEntryChangeValueMessageLocation $location = null;
    public ?EventEntryChangeValueMessageReaction $reaction = null;
    public ?EventEntryChangeValueMessageUnsupported $unsupported = null;
    /** @var array<int,array<string,mixed>>|null */
    public ?array $contacts = null;
    public ?bool $edited = null;
    public ?bool $deleted = null;

    public function forwarded()
    {
        if (!isset($this->context)) {
            return 0;
        } elseif (isset($this->context->forwarded) && $this->context->forwarded) {
            return 1;
        } elseif (isset($this->context->frequently_forwarded) && $this->context->frequently_forwarded) {
            return 2;
        } else {
            return 0;
        }
    }

    public function replyOf()
    {
        if ($this->isReply()) {
            return $this->context->id;
        }

        return null;
    }

    public function isReply()
    {
        return isset($this->context) && isset($this->context->id);
    }
}
