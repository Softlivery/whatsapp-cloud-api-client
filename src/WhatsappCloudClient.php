<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Client\MediaClient;
use Softlivery\WhatsappCloudApiClient\Client\MessagesClient;
use Softlivery\WhatsappCloudApiClient\Client\TemplatesClient;
use Softlivery\WhatsappCloudApiClient\Client\WabaClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;

final class WhatsappCloudClient
{
    public function __construct(private readonly string $accessToken, private readonly HttpClient $httpClient)
    {
    }

    public function messages(string $phoneNumberId): MessagesClient
    {
        return new MessagesClient($this->accessToken, $phoneNumberId, $this->httpClient);
    }

    public function media(string $phoneNumberId): MediaClient
    {
        return new MediaClient($this->accessToken, $phoneNumberId, $this->httpClient);
    }

    public function templates(string $wabaId): TemplatesClient
    {
        return new TemplatesClient($this->accessToken, $wabaId, $this->httpClient);
    }

    public function waba(string $wabaId): WabaClient
    {
        return new WabaClient($this->accessToken, $wabaId, $this->httpClient);
    }
}
