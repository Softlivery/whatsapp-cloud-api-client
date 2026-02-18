<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class ReactionMessage extends Message
{
    public function __construct(string $to, private readonly string $message_id, private readonly string $emoji)
    {
        parent::__construct('reaction', $to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $message['reaction'] = [
            'message_id' => $this->message_id,
            'emoji' => $this->emoji,
        ];

        return $message;
    }
}
