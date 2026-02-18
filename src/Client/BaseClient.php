<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\BearerAuthMiddleware;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\ErrorRaisingClient;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\StaticTokenProvider;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

abstract class BaseClient
{
    private HttpClient $authedHttpClient;

    public function __construct(protected readonly string $accessToken, HttpClient $httpClient)
    {
        $errorRaisingClient = new ErrorRaisingClient($httpClient);
        $this->authedHttpClient = new BearerAuthMiddleware($errorRaisingClient, new StaticTokenProvider($accessToken));
    }

    protected function sendRequest(ApiRequest $request): HttpResponse
    {
        return $this->authedHttpClient->send($request);
    }
}
