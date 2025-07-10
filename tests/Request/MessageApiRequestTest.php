<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Request;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Message\Message;
use Softlivery\WhatsappCloudApiClient\Request\MessageApiRequest;

class MessageApiRequestTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';
        $timeout = 120;

        $mockMessage = $this->createMock(Message::class);

        // Act
        $messageApiRequest = new MessageApiRequest($accessToken, $fromPhoneNumberId, $mockMessage, $timeout);

        // Assert
        $expectedPath = "$fromPhoneNumberId/messages";
        $this->assertEquals($expectedPath, $messageApiRequest->getPath());
        $this->assertEquals('POST', $messageApiRequest->getMethod());
        $this->assertEquals([
            'Authorization' => "Bearer $accessToken",
        ], $messageApiRequest->getHeaders());
        $this->assertEquals($timeout, $messageApiRequest->getTimeout());
    }

    public function testGetBodyReturnsJsonFromMessage()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';

        $mockMessage = $this->createMock(Message::class);
        $mockMessage->expects($this->once())
            ->method('toJson')
            ->willReturn('{"key":"value"}'); // Simulating the output of toJson()

        $messageApiRequest = new MessageApiRequest($accessToken, $fromPhoneNumberId, $mockMessage);

        // Act
        $body = $messageApiRequest->getBody();

        // Assert
        $this->assertEquals('{"key":"value"}', $body);
    }

    public function testDefaultTimeoutIs60()
    {
        // Arrange
        $accessToken = 'test_access_token';
        $fromPhoneNumberId = '1234567890';
        $mockMessage = $this->createMock(Message::class);

        // Act
        $messageApiRequest = new MessageApiRequest($accessToken, $fromPhoneNumberId, $mockMessage);

        // Assert
        $this->assertEquals(60, $messageApiRequest->getTimeout());
    }
}
