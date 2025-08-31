<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

// https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages
class MessageApiResponse extends ApiResponse
{
    public function __construct(HttpResponse $response)
    {
        parent::__construct($response);
    }

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
