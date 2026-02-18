<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class AudioMessage extends Message
{
    public function __construct(
        string $to,
        private readonly ?string $id = null,
        private readonly ?string $link = null,
        ?string $reply_to = null
    ) {
        parent::__construct('audio', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $audio = [];

        if ($this->id !== null) {
            $audio['id'] = $this->id;
        }
        if ($this->link !== null) {
            $audio['link'] = $this->link;
        }

        $message['audio'] = $audio;
        return $message;
    }
}
