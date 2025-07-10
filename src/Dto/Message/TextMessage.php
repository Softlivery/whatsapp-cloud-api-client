<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

class TextMessage extends Message
{
    public string $body;
    public bool $preview_url;

    public function __construct(string $to, string $body, bool $preview_url = false, ?string $reply_to = null)
    {
        $this->body = $body;
        $this->preview_url = $preview_url;

        parent::__construct("text", $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();

        $message["text"] = [
            "preview_url" => $this->preview_url,
            "body" => $this->body,
        ];

        return $message;
    }
}
