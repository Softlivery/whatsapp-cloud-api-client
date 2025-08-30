<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

class CodeExchangeApiResponse extends ApiResponse
{

    private HttpResponse $httpResponse;

    public function __construct(HttpResponse $response)
    {
        $this->httpResponse = $response;
    }

    public function getAccessToken(): string
    {
        return $this->httpResponse->getDecodedBody()["access_token"];
    }
}
