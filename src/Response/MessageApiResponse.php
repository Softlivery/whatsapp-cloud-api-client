<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

// https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages
class MessageApiResponse extends ApiResponse
{
    /**
     * Contacts returned by the send message operation.
     *
     * @return array<int, array<string,mixed>>
     */
    public function getContacts(): array
    {
        return $this->getArray('contacts');
    }

    /**
     * Messages returned by the send message operation.
     *
     * @return array<int, array<string,mixed>>
     */
    public function getMessages(): array
    {
        return $this->getArray('messages');
    }
}
