<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class VideoMessage extends Message
{
    public function __construct(
        string $to,
        private readonly ?string $id = null,
        private readonly ?string $link = null,
        private readonly ?string $caption = null,
        ?string $reply_to = null
    ) {
        parent::__construct('video', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $video = [];

        if ($this->id !== null) {
            $video['id'] = $this->id;
        }
        if ($this->link !== null) {
            $video['link'] = $this->link;
        }
        if ($this->caption !== null) {
            $video['caption'] = $this->caption;
        }

        $message['video'] = $video;
        return $message;
    }
}
