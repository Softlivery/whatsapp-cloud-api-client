<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use Softlivery\WhatsappCloudApiClient\Dto\Message\AudioMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\ContactsMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\DocumentMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\ImageMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\InteractiveMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\LocationMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\Message;
use Softlivery\WhatsappCloudApiClient\Dto\Message\ReactionMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\StickerMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\TemplateMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\TextMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Message\VideoMessage;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

final class MessagesClient extends BaseClient
{
    public function __construct(string $accessToken, private readonly string $fromPhoneNumberId, HttpClient $httpClient)
    {
        parent::__construct($accessToken, $httpClient);
    }

    public function sendMessage(Message|array $message): MessageApiResponse
    {
        $payload = $message instanceof Message ? $message->toArray() : $message;
        $response = $this->sendRequest(RequestFactory::messageSend($this->fromPhoneNumberId, $payload));
        return new MessageApiResponse($response);
    }

    public function sendText(string $to, string $body, bool $previewUrl = false, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new TextMessage($to, $body, $previewUrl, $replyTo));
    }

    public function sendImage(string $to, ?string $id = null, ?string $link = null, ?string $caption = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new ImageMessage($to, $id, $link, $caption, $replyTo));
    }

    public function sendVideo(string $to, ?string $id = null, ?string $link = null, ?string $caption = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new VideoMessage($to, $id, $link, $caption, $replyTo));
    }

    public function sendAudio(string $to, ?string $id = null, ?string $link = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new AudioMessage($to, $id, $link, $replyTo));
    }

    public function sendDocument(
        string $to,
        ?string $id = null,
        ?string $link = null,
        ?string $caption = null,
        ?string $filename = null,
        ?string $replyTo = null
    ): MessageApiResponse {
        return $this->sendMessage(new DocumentMessage($to, $id, $link, $caption, $filename, $replyTo));
    }

    public function sendSticker(string $to, ?string $id = null, ?string $link = null, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new StickerMessage($to, $id, $link, $replyTo));
    }

    public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null,
        ?string $replyTo = null
    ): MessageApiResponse {
        return $this->sendMessage(new LocationMessage($to, $latitude, $longitude, $name, $address, $replyTo));
    }

    public function sendReaction(string $to, string $messageId, string $emoji): MessageApiResponse
    {
        return $this->sendMessage(new ReactionMessage($to, $messageId, $emoji));
    }

    /** @param array<string,mixed> $interactive */
    public function sendInteractive(string $to, array $interactive, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new InteractiveMessage($to, $interactive, $replyTo));
    }

    /** @param array<int,array<string,mixed>> $components */
    public function sendTemplate(string $to, string $templateName, string $languageCode, array $components = [], ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new TemplateMessage($to, $templateName, $languageCode, $components, $replyTo));
    }

    /** @param array<int,array<string,mixed>> $contacts */
    public function sendContacts(string $to, array $contacts, ?string $replyTo = null): MessageApiResponse
    {
        return $this->sendMessage(new ContactsMessage($to, $contacts, $replyTo));
    }

    public function markAsRead(string $messageId): MessageApiResponse
    {
        $response = $this->sendRequest(RequestFactory::messageMarkAsRead($this->fromPhoneNumberId, $messageId));
        return new MessageApiResponse($response);
    }
}
