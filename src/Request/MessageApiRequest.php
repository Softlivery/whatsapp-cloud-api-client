<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

use JsonSerializable;
use Softlivery\WhatsappCloudApiClient\Dto\Message\Message;

class MessageApiRequest extends AuthorizedApiRequest
{
    private Message $message;

    public function __construct(string $access_token, string $from_phone_number_id, Message $message, int $timeout = 60)
    {
        $this->message = $message;
        parent::__construct($access_token, "$from_phone_number_id/messages", "POST", $timeout);
    }

    public function getBody(): ?string
    {
        // Prefer JsonSerializable DTOs; if Message::toJson exists, keep using it for compatibility
        if ($this->message instanceof JsonSerializable) {
            return json_encode($this->message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $this->message->toJson();
    }
}
