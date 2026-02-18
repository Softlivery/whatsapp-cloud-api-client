<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class StickerMessage extends Message
{
    public function __construct(
        string $to,
        private readonly ?string $id = null,
        private readonly ?string $link = null,
        ?string $reply_to = null
    ) {
        parent::__construct('sticker', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $sticker = [];

        if ($this->id !== null) {
            $sticker['id'] = $this->id;
        }
        if ($this->link !== null) {
            $sticker['link'] = $this->link;
        }

        $message['sticker'] = $sticker;
        return $message;
    }
}
