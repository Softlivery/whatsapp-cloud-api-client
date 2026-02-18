<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class InteractiveMessage extends Message
{
    /** @param array<string,mixed> $interactive */
    public function __construct(string $to, private readonly array $interactive, ?string $reply_to = null)
    {
        parent::__construct('interactive', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $message['interactive'] = $this->interactive;
        return $message;
    }
}
