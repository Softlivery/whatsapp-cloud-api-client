<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Dto\Message\TextMessage;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\BearerAuthMiddleware;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\ErrorRaisingClient;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\StaticTokenProvider;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

class ApiClient
{
    private string $access_token;
    private string $from_phone_number_id;
    private HttpClient $httpClient;
    private BearerAuthMiddleware $authedHttpClient;

    public function __construct(string $access_token, string $from_phone_number_id, HttpClient $httpClient)
    {
        $this->access_token = $access_token;
        $this->from_phone_number_id = $from_phone_number_id;
        $this->httpClient = new ErrorRaisingClient($httpClient);
        $this->authedHttpClient = new BearerAuthMiddleware($this->httpClient, new StaticTokenProvider($access_token));
    }

    public function sendTextMessage(string $to, string $body, bool $preview_url = false, ?string $reply_to = null): MessageApiResponse
    {
        $message = new TextMessage($to, $body, $preview_url, $reply_to);
        $request = RequestFactory::messageSend($this->from_phone_number_id, $message->toArray());
        $response = $this->authedHttpClient->send($request);
        return new MessageApiResponse($response);
    }
}
