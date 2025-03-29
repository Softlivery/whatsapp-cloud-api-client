<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessage
{
    public string $from;
    public string $id;
    public string $timestamp;
    public string $type;
    public EventEntryChangeValueMessageAudio $audio;
    public EventEntryChangeValueMessageButton $button;
    public EventEntryChangeValueMessageContext $context;
    public EventEntryChangeValueMessageDocument $document;
    public EventEntryChangeValueMessageIdentity $identity;
    public EventEntryChangeValueMessageImage $image;
    public EventEntryChangeValueMessageInteractive $interactive;
    public EventEntryChangeValueMessageOrder $order;
    public EventEntryChangeValueMessageReferral $referral;
    public EventEntryChangeValueMessageSticker $sticker;
    public EventEntryChangeValueMessageSystem $system;
    public EventEntryChangeValueMessageText $text;
    public EventEntryChangeValueMessageVideo $video;

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
