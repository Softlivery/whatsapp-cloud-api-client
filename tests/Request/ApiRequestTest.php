<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Request;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

class ApiRequestTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/test/path';
        $method = 'POST';
        $timeout = 120;

        // Act
        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method, $timeout]);

        // Assert
        $this->assertEquals($path, $apiRequest->getPath());
        $this->assertEquals($method, $apiRequest->getMethod());
        $this->assertEquals($timeout, $apiRequest->getTimeout());
        $this->assertEquals(['Authorization' => "Bearer $accessToken", 'Content-Type' => 'application/json'], $apiRequest->getHeaders());
    }

    public function testDefaultTimeoutIsSetTo60()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/test/path';
        $method = 'GET';

        // Act
        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method]);

        // Assert
        $this->assertEquals(60, $apiRequest->getTimeout());
    }

    public function testGetHeadersReturnsCorrectHeaders()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/test/path';
        $method = 'POST';

        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method]);

        // Act
        $headers = $apiRequest->getHeaders();

        // Assert
        $this->assertIsArray($headers);
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals("Bearer $accessToken", $headers['Authorization']);
    }

    public function testGetMethodReturnsCorrectMethod()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/test/path';
        $method = 'PUT';

        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method]);

        // Act
        $resultMethod = $apiRequest->getMethod();

        // Assert
        $this->assertEquals('PUT', $resultMethod);
    }

    public function testGetPathReturnsCorrectPath()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/another/test/path';
        $method = 'GET';

        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method]);

        // Act
        $resultPath = $apiRequest->getPath();

        // Assert
        $this->assertEquals('/another/test/path', $resultPath);
    }

    public function testGetBodyReturnsNullByDefault()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $path = '/test/path';
        $method = 'POST';

        $apiRequest = $this->getMockForAbstractClass(ApiRequest::class, [$accessToken, $path, $method]);

        // Act
        $body = $apiRequest->getBody();

        // Assert
        $this->assertNull($body);
    }
}
