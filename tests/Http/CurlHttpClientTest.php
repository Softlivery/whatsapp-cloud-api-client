<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Http;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Softlivery\WhatsappCloudApiClient\Http\CurlHttpClient;

class CurlHttpClientTest extends TestCase
{
    public function testBuildUrlAddsBaseAndVersion(): void
    {
        $client = new CurlHttpClient('https://graph.facebook.com', 'v25.0');

        $this->assertSame(
            'https://graph.facebook.com/v25.0/123/messages',
            $this->invokeBuildUrl($client, '123/messages')
        );
    }

    public function testBuildUrlKeepsAbsoluteUrl(): void
    {
        $client = new CurlHttpClient('https://graph.facebook.com', 'v25.0');

        $absolute = 'https://example.com/custom/endpoint';
        $this->assertSame($absolute, $this->invokeBuildUrl($client, $absolute));
    }

    public function testBuildUrlDoesNotDuplicateVersion(): void
    {
        $client = new CurlHttpClient('https://graph.facebook.com', 'v25.0');

        $this->assertSame(
            'https://graph.facebook.com/v25.0/123/messages',
            $this->invokeBuildUrl($client, 'v25.0/123/messages')
        );
    }

    public function testBuildUrlWithoutVersion(): void
    {
        $client = new CurlHttpClient('https://graph.facebook.com', null);

        $this->assertSame(
            'https://graph.facebook.com/123/messages',
            $this->invokeBuildUrl($client, '/123/messages')
        );
    }

    private function invokeBuildUrl(CurlHttpClient $client, string $path): string
    {
        $method = new ReflectionMethod($client, 'buildUrl');
        $method->setAccessible(true);
        return (string)$method->invoke($client, $path);
    }
}
