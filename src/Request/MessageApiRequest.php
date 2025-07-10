<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

use Softlivery\WhatsappCloudApiClient\Dto\Message\Message;

class MessageApiRequest extends ApiRequest
{
    private Message $message;

    public function __construct(string $access_token, string $from_phone_number_id, Message $message, int $timeout = 60)
    {
        $this->message = $message;

        parent::__construct($access_token, "$from_phone_number_id/messages", "POST", $timeout);
    }

    public function getBody(): ?string
    {
        return $this->message->toJson();
    }
}
