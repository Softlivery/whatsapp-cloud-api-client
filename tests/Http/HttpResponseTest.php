<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Http;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

class HttpResponseTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        // Arrange
        $body = '{"success":true}';
        $httpStatusCode = 200;
        $headers = ['Content-Type' => 'application/json'];

        // Act
        $response = new HttpResponse($body, $httpStatusCode, $headers);

        // Assert
        $this->assertEquals($body, $this->getPrivateProperty($response, 'body'));
        $this->assertEquals($httpStatusCode, $this->getPrivateProperty($response, 'http_status_code'));
        $this->assertEquals($headers, $this->getPrivateProperty($response, 'headers'));
        $this->assertEquals(['success' => true], $this->getPrivateProperty($response, 'decoded_body'));
    }

    /**
     * Helper method to access private or protected properties via reflection.
     */
    private function getPrivateProperty(object $object, string $propertyName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public function testGetHttpStatusCode()
    {
        // Arrange
        $httpStatusCode = 404;
        $response = new HttpResponse('{"error":"Not Found"}', $httpStatusCode);

        // Act & Assert
        $this->assertEquals($httpStatusCode, $response->getHttpStatusCode());
    }

    public function testGetHeaders()
    {
        // Arrange
        $headers = ['Authorization' => 'Bearer token'];
        $response = new HttpResponse('{"message":"OK"}', 200, $headers);

        // Act & Assert
        $this->assertEquals($headers, $response->getHeaders());
    }

    public function testGetDecodedBody()
    {
        // Arrange
        $body = '{"key":"value"}';
        $response = new HttpResponse($body);

        // Act & Assert
        $this->assertEquals(['key' => 'value'], $response->getDecodedBody());
    }

    public function testGetDecodedBodyInvalidJson()
    {
        // Arrange
        $invalidBody = 'Not a JSON string';
        $response = new HttpResponse($invalidBody);

        // Act & Assert
        $this->assertEquals([], $response->getDecodedBody());
    }

    public function testIsErrorReturnsTrue()
    {
        // Arrange
        $body = '{"error":{"message":"Something went wrong"}}';
        $response = new HttpResponse($body);

        // Act & Assert
        $this->assertTrue($response->isError());
    }

    public function testIsErrorReturnsFalse()
    {
        // Arrange
        $body = '{"success":true}';
        $response = new HttpResponse($body);

        // Act & Assert
        $this->assertFalse($response->isError());
    }
}
