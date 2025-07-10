<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Dto\Message\TextMessage;
use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\MessageApiRequest;
use Softlivery\WhatsappCloudApiClient\Response\ErrorApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

class ApiClient
{
    private string $access_token;
    private string $from_phone_number_id;
    private HttpClient $httpClient;

    public function __construct(string $access_token, string $from_phone_number_id, HttpClient $httpClient)
    {
        $this->access_token = $access_token;
        $this->from_phone_number_id = $from_phone_number_id;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ApiResponseException
     */
    public function sendTextMessage(string $to, string $body, bool $preview_url = false, ?string $reply_to = null): MessageApiResponse
    {
        $message = new TextMessage($to, $body, $preview_url, $reply_to);
        $request = new MessageApiRequest($this->access_token, $this->from_phone_number_id, $message);
        $response = $this->httpClient->send($request);

        if ($response->isError()) {
            throw (new ErrorApiResponse($response))::throw();
        }

        return new MessageApiResponse($response);
    }
}
