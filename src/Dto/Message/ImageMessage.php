<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class ImageMessage extends Message
{
    public function __construct(
        string $to,
        private readonly ?string $id = null,
        private readonly ?string $link = null,
        private readonly ?string $caption = null,
        ?string $reply_to = null
    ) {
        parent::__construct('image', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $image = [];

        if ($this->id !== null) {
            $image['id'] = $this->id;
        }
        if ($this->link !== null) {
            $image['link'] = $this->link;
        }
        if ($this->caption !== null) {
            $image['caption'] = $this->caption;
        }

        $message['image'] = $image;
        return $message;
    }
}
