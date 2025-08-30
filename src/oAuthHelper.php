<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\CodeExchangeApiRequest;
use Softlivery\WhatsappCloudApiClient\Response\CodeExchangeApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\ErrorApiResponse;

class oAuthHelper
{
    private string $client_id;
    private string $client_secret;
    private HttpClient $httpClient;

    public function __construct(string $client_id, string $client_secret, HttpClient $httpClient)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ApiResponseException
     */
    public function exchangeCode(string $code, string $redirect_url): CodeExchangeApiResponse
    {
        $request = new CodeExchangeApiRequest($this->client_id, $this->client_secret, $code, $redirect_url);
        $response = $this->httpClient->send($request);

        if ($response->isError()) {
            throw (new ErrorApiResponse($response))::throw();
        }

        return new CodeExchangeApiResponse($response);
    }
}
