<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class DocumentMessage extends Message
{
    public function __construct(
        string $to,
        private readonly ?string $id = null,
        private readonly ?string $link = null,
        private readonly ?string $caption = null,
        private readonly ?string $filename = null,
        ?string $reply_to = null
    ) {
        parent::__construct('document', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $document = [];

        if ($this->id !== null) {
            $document['id'] = $this->id;
        }
        if ($this->link !== null) {
            $document['link'] = $this->link;
        }
        if ($this->caption !== null) {
            $document['caption'] = $this->caption;
        }
        if ($this->filename !== null) {
            $document['filename'] = $this->filename;
        }

        $message['document'] = $document;
        return $message;
    }
}
