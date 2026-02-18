<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Request;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

class ApiRequestTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        $path = '/test/path';
        $method = 'POST';
        $timeout = 120;
        $apiRequest = new ApiRequest($path, $method, $timeout);
        $this->assertEquals($path, $apiRequest->getPath());
        $this->assertEquals($method, $apiRequest->getMethod());
        $this->assertEquals($timeout, $apiRequest->getTimeout());
        $this->assertEquals(['Content-Type' => 'application/json'], $apiRequest->getHeaders());
    }

    public function testDefaultTimeoutIsSetTo60()
    {
        $apiRequest = new ApiRequest('/test/path', 'GET');
        $this->assertEquals(60, $apiRequest->getTimeout());
    }

    public function testGetHeadersReturnsCorrectHeaders()
    {
        $apiRequest = new ApiRequest('/test/path', 'POST');
        $headers = $apiRequest->getHeaders();

        $this->assertIsArray($headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('application/json', $headers['Content-Type']);
    }

    public function testGetMethodReturnsCorrectMethod()
    {
        $apiRequest = new ApiRequest('/test/path', 'PUT');
        $resultMethod = $apiRequest->getMethod();
        $this->assertEquals('PUT', $resultMethod);
    }

    public function testGetPathReturnsCorrectPath()
    {
        $apiRequest = new ApiRequest('/another/test/path', 'GET');
        $resultPath = $apiRequest->getPath();
        $this->assertEquals('/another/test/path', $resultPath);
    }

    public function testGetBodyReturnsNullByDefault()
    {
        $apiRequest = new ApiRequest('/test/path', 'POST');
        $body = $apiRequest->getBody();
        $this->assertNull($body);
    }
}
