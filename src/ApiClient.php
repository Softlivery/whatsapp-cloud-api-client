<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Client\MessagesClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

class ApiClient
{
    private MessagesClient $messagesClient;

    public function __construct(string $access_token, string $from_phone_number_id, HttpClient $httpClient)
    {
        $this->messagesClient = new MessagesClient($access_token, $from_phone_number_id, $httpClient);
    }

    public function sendTextMessage(string $to, string $body, bool $preview_url = false, ?string $reply_to = null): MessageApiResponse
    {
        return $this->messagesClient->sendText($to, $body, $preview_url, $reply_to);
    }

    public function sendTemplateMessage(string $to, string $templateName, string $languageCode, array $components = [], ?string $replyTo = null): MessageApiResponse
    {
        return $this->messagesClient->sendTemplate($to, $templateName, $languageCode, $components, $replyTo);
    }

    public function sendImageMessage(string $to, ?string $id = null, ?string $link = null, ?string $caption = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->messagesClient->sendImage($to, $id, $link, $caption, $replyTo);
    }

    public function sendDocumentMessage(string $to, ?string $id = null, ?string $link = null, ?string $caption = null, ?string $filename = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->messagesClient->sendDocument($to, $id, $link, $caption, $filename, $replyTo);
    }

    public function markAsRead(string $messageId): MessageApiResponse
    {
        return $this->messagesClient->markAsRead($messageId);
    }
}
