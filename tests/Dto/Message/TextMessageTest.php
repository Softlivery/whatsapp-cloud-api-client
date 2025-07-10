<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Dto\Message;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Message\TextMessage;

class TextMessageTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        // Arrange
        $to = 'recipient_number';
        $body = 'This is a sample text message.';
        $previewUrl = true;
        $replyTo = 'reply_message_id';

        // Act
        $textMessage = new TextMessage($to, $body, $previewUrl, $replyTo);

        // Assert
        $this->assertEquals('individual', $textMessage->recipient_type);
        $this->assertEquals('whatsapp', $textMessage->messaging_product);
        $this->assertEquals('text', $textMessage->type);
        $this->assertEquals($to, $textMessage->to);
        $this->assertEquals($body, $textMessage->body);
        $this->assertEquals($previewUrl, $textMessage->preview_url);
        $this->assertEquals($replyTo, $textMessage->reply_to);
    }

    public function testToArrayWithoutReplyTo()
    {
        // Arrange
        $to = 'recipient_number';
        $body = 'This is a sample text message.';
        $previewUrl = false;

        $textMessage = new TextMessage($to, $body, $previewUrl);

        // Act
        $result = $textMessage->toArray();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('messaging_product', $result);
        $this->assertArrayHasKey('recipient_type', $result);
        $this->assertArrayHasKey('to', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayNotHasKey('context', $result);

        $this->assertEquals('whatsapp', $result['messaging_product']);
        $this->assertEquals('individual', $result['recipient_type']);
        $this->assertEquals($to, $result['to']);
        $this->assertEquals('text', $result['type']);
        $this->assertEquals([
            "preview_url" => $previewUrl,
            "body" => $body,
        ], $result['text']);
    }

    public function testToArrayWithReplyTo()
    {
        // Arrange
        $to = 'recipient_number';
        $body = 'This is a sample text message.';
        $previewUrl = true;
        $replyTo = 'reply_message_id';

        $textMessage = new TextMessage($to, $body, $previewUrl, $replyTo);

        // Act
        $result = $textMessage->toArray();

        // Assert
        $this->assertArrayHasKey('context', $result);
        $this->assertArrayHasKey('message_id', $result['context']);
        $this->assertEquals($replyTo, $result['context']['message_id']);
        $this->assertEquals([
            "preview_url" => $previewUrl,
            "body" => $body,
        ], $result['text']);
    }
}
