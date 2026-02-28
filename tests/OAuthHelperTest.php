<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\OAuthHelper;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

class OAuthHelperTest extends TestCase
{
    public function testExchangeCodeAllowsEmptyRedirectUri(): void
    {
        $httpClient = new class implements HttpClient {
            public ?ApiRequest $lastRequest = null;

            public function send(ApiRequest $request): HttpResponse
            {
                $this->lastRequest = $request;
                return new HttpResponse('{"access_token":"token_123"}', 200);
            }
        };

        $helper = new OAuthHelper('client_123', 'secret_abc', $httpClient);
        $response = $helper->exchangeCode('code_xyz', '');

        $this->assertSame('token_123', $response->getAccessToken());
        $this->assertNotNull($httpClient->lastRequest);

        $parts = parse_url($httpClient->lastRequest->getPath());
        parse_str($parts['query'] ?? '', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertSame('', $query['redirect_uri']);
    }

    public function testExchangeCodeIncludesRedirectUriWhenProvided(): void
    {
        $httpClient = new class implements HttpClient {
            public ?ApiRequest $lastRequest = null;

            public function send(ApiRequest $request): HttpResponse
            {
                $this->lastRequest = $request;
                return new HttpResponse('{"access_token":"token_456"}', 200);
            }
        };

        $helper = new OAuthHelper('client_123', 'secret_abc', $httpClient);
        $response = $helper->exchangeCode('code_xyz', 'https://app.example.test/callback');

        $this->assertSame('token_456', $response->getAccessToken());
        $this->assertNotNull($httpClient->lastRequest);

        $parts = parse_url($httpClient->lastRequest->getPath());
        parse_str($parts['query'] ?? '', $query);
        $this->assertSame('https://app.example.test/callback', $query['redirect_uri'] ?? null);
    }
}
