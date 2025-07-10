<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Dto\Message;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Message\Message;

class MessageTest extends TestCase
{
    public function testConstructorInitializesPropertiesCorrectly()
    {
        // Arrange
        $type = 'text';
        $to = 'recipient_number';
        $replyTo = 'reply_message_id';

        // Act
        $message = $this->getMockForAbstractClass(Message::class, [$type, $to, $replyTo]);

        // Assert
        $this->assertEquals('whatsapp', $message->messaging_product);
        $this->assertEquals('individual', $message->recipient_type);
        $this->assertEquals($to, $message->to);
        $this->assertEquals($replyTo, $message->reply_to);
        $this->assertEquals($type, $message->type);
    }

    public function testToArrayWithoutReplyTo()
    {
        // Arrange
        $type = 'text';
        $to = 'recipient_number';

        $message = $this->getMockForAbstractClass(Message::class, [$type, $to]);

        // Act
        $result = $message->toArray();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('messaging_product', $result);
        $this->assertArrayHasKey('recipient_type', $result);
        $this->assertArrayHasKey('to', $result);
        $this->assertArrayHasKey('type', $result);

        $this->assertEquals('whatsapp', $result['messaging_product']);
        $this->assertEquals('individual', $result['recipient_type']);
        $this->assertEquals($to, $result['to']);
        $this->assertEquals($type, $result['type']);
        $this->assertArrayNotHasKey('context', $result);
    }

    public function testToArrayWithReplyTo()
    {
        // Arrange
        $type = 'text';
        $to = 'recipient_number';
        $replyTo = 'reply_message_id';

        $message = $this->getMockForAbstractClass(Message::class, [$type, $to, $replyTo]);

        // Act
        $result = $message->toArray();

        // Assert
        $this->assertArrayHasKey('context', $result);
        $this->assertArrayHasKey('message_id', $result['context']);
        $this->assertEquals($replyTo, $result['context']['message_id']);
    }

    public function testToJson()
    {
        // Arrange
        $type = 'text';
        $to = 'recipient_number';
        $replyTo = 'reply_message_id';

        $message = $this->getMockForAbstractClass(Message::class, [$type, $to, $replyTo]);

        // Act
        $json = $message->toJson();

        // Assert
        $this->assertJson($json);
        $decodedJson = json_decode($json, true);

        $this->assertArrayHasKey('messaging_product', $decodedJson);
        $this->assertArrayHasKey('recipient_type', $decodedJson);
        $this->assertArrayHasKey('to', $decodedJson);
        $this->assertArrayHasKey('type', $decodedJson);
        $this->assertArrayHasKey('context', $decodedJson);
        $this->assertEquals($replyTo, $decodedJson['context']['message_id']);
    }
}
