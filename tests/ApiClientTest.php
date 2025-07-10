<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\ApiClient;
use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Request\MessageApiRequest;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

class ApiClientTest extends TestCase
{
    public function testConstructorInitializesDependencies()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';
        $httpClient = $this->createMock(HttpClient::class);

        // Act
        $apiClient = new ApiClient($accessToken, $fromPhoneNumberId, $httpClient);

        // Assert
        $this->assertInstanceOf(ApiClient::class, $apiClient);
    }

    public function testSendTextMessageReturnsMessageApiResponse()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';
        $to = '+11234567890';
        $body = 'Hello, world!';
        $previewUrl = true;

        $httpClient = $this->createMock(HttpClient::class);
        $httpResponse = $this->createMock(HttpResponse::class);

        $httpResponse->method('isError')->willReturn(false);

        $httpClient->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(MessageApiRequest::class))
            ->willReturn($httpResponse);

        $apiClient = new ApiClient($accessToken, $fromPhoneNumberId, $httpClient);

        // Act
        $response = $apiClient->sendTextMessage($to, $body, $previewUrl);

        // Assert
        $this->assertInstanceOf(MessageApiResponse::class, $response);
    }

    public function testSendTextMessageThrowsExceptionOnErrorResponse()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';
        $to = '+11234567890';
        $body = 'Hello, world!';

        $httpClient = $this->createMock(HttpClient::class);
        $httpResponse = $this->createMock(HttpResponse::class);

        $httpResponse->method('isError')->willReturn(true);

        $httpClient->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(MessageApiRequest::class))
            ->willReturn($httpResponse);

        $this->expectException(ApiResponseException::class);

        $apiClient = new ApiClient($accessToken, $fromPhoneNumberId, $httpClient);

        // Act
        $apiClient->sendTextMessage($to, $body);
    }
}
