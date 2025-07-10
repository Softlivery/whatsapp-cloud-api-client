<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

//https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages
class MessageApiResponse extends ApiResponse
{
    private HttpResponse $httpResponse;

    public function __construct(HttpResponse $response)
    {
        $this->httpResponse = $response;
    }

    public function getContacts(): array
    {
        return $this->httpResponse->getDecodedBody()["contacts"];
    }

    public function getMessages(): array
    {
        return $this->httpResponse->getDecodedBody()["messages"];
    }
}
