<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

abstract class Message
{
    public string $messaging_product = "whatsapp";
    public string $recipient_type = "individual";
    public string $to;
    public ?string $reply_to = null;
    public string $type;

    public function __construct(string $type, string $to, ?string $reply_to = null)
    {
        $this->type = $type;
        $this->to = $to;
        $this->reply_to = $reply_to;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $message = [
            "messaging_product" => $this->messaging_product,
            "recipient_type" => $this->recipient_type,
            "to" => $this->to,
            "type" => $this->type,
        ];

        if ($this->reply_to) {
            $message["context"]["message_id"] = $this->reply_to;
        }

        return $message;
    }
}
