<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

class CodeExchangeApiResponse extends ApiResponse
{
    public function getAccessToken(): string
    {
        return $this->httpResponse->getDecodedBody()["access_token"];
    }
}
