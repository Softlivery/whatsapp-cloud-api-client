<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Response;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Response\MessageApiResponse;

class MessageApiResponseTest extends TestCase
{
    public function testConstructorInitializesHttpResponse()
    {
        // Arrange
        $httpResponseMock = $this->createMock(HttpResponse::class);

        // Act
        $messageApiResponse = new MessageApiResponse($httpResponseMock);

        // Assert
        $this->assertInstanceOf(MessageApiResponse::class, $messageApiResponse);
    }

    public function testGetContactsReturnsExpectedData()
    {
        // Arrange
        $decodedBody = [
            "contacts" => [
                ["id" => "12345", "input" => "+1234567890"],
            ],
            "messages" => [],
        ];

        $httpResponseMock = $this->createMock(HttpResponse::class);
        $httpResponseMock->method('getDecodedBody')->willReturn($decodedBody);

        $messageApiResponse = new MessageApiResponse($httpResponseMock);

        // Act
        $contacts = $messageApiResponse->getContacts();

        // Assert
        $this->assertIsArray($contacts);
        $this->assertEquals($decodedBody["contacts"], $contacts);
    }

    public function testGetMessagesReturnsExpectedData()
    {
        // Arrange
        $decodedBody = [
            "contacts" => [],
            "messages" => [
                ["id" => "abc123", "text" => "Hello, world!"],
            ],
        ];

        $httpResponseMock = $this->createMock(HttpResponse::class);
        $httpResponseMock->method('getDecodedBody')->willReturn($decodedBody);

        $messageApiResponse = new MessageApiResponse($httpResponseMock);

        // Act
        $messages = $messageApiResponse->getMessages();

        // Assert
        $this->assertIsArray($messages);
        $this->assertEquals($decodedBody["messages"], $messages);
    }
}
