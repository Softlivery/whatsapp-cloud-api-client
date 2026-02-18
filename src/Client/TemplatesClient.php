<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\GenericApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\TemplatesApiResponse;

final class TemplatesClient extends BaseClient
{
    public function __construct(string $accessToken, private readonly string $wabaId, HttpClient $httpClient)
    {
        parent::__construct($accessToken, $httpClient);
    }

    /** @param array<string,mixed> $params */
    public function list(array $params = []): TemplatesApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getMessageTemplates($this->wabaId, $params));
        return new TemplatesApiResponse($response);
    }

    /** @param array<string,mixed> $payload */
    public function create(array $payload): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::createMessageTemplate($this->wabaId, $payload));
        return new GenericApiResponse($response);
    }

    /** @param array<string,mixed> $extraQuery */
    public function delete(string $name, array $extraQuery = []): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::deleteMessageTemplate($this->wabaId, $name, $extraQuery));
        return new GenericApiResponse($response);
    }

    /** @param array<string,mixed> $params */
    public function analytics(array $params = []): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getTemplateAnalytics($this->wabaId, $params));
        return new GenericApiResponse($response);
    }
}
