<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class ContactsMessage extends Message
{
    /** @param array<int,array<string,mixed>> $contacts */
    public function __construct(string $to, private readonly array $contacts, ?string $reply_to = null)
    {
        parent::__construct('contacts', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $message['contacts'] = $this->contacts;
        return $message;
    }
}
