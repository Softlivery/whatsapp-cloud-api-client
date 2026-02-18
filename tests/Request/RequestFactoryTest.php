<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Request;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;

class RequestFactoryTest extends TestCase
{
    public function testMessageMarkAsReadRequest(): void
    {
        $request = RequestFactory::messageMarkAsRead('123', 'wamid.abc');

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('123/messages', $request->getPath());
        $body = json_decode((string)$request->getBody(), true);
        $this->assertSame('read', $body['status']);
        $this->assertSame('wamid.abc', $body['message_id']);
    }

    public function testGetMessageTemplatesRequest(): void
    {
        $request = RequestFactory::getMessageTemplates('waba123', ['limit' => 5]);

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('waba123/message_templates?limit=5', $request->getPath());
    }

    public function testDeleteMessageTemplateRequest(): void
    {
        $request = RequestFactory::deleteMessageTemplate('waba123', 'welcome_template');

        $this->assertSame('DELETE', $request->getMethod());
        $this->assertStringContainsString('waba123/message_templates?', $request->getPath());
        $this->assertStringContainsString('name=welcome_template', $request->getPath());
    }

    public function testUpdateBusinessProfileRequest(): void
    {
        $request = RequestFactory::updateBusinessProfile('phone123', ['about' => 'Support team']);

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('phone123/whatsapp_business_profile', $request->getPath());
        $body = json_decode((string)$request->getBody(), true);
        $this->assertSame('Support team', $body['about']);
    }

    public function testCustomRequestWithBodyAndQuery(): void
    {
        $request = RequestFactory::custom('waba123/custom_edge', 'post', ['a' => 'b'], ['x' => 1]);

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('waba123/custom_edge?a=b', $request->getPath());
        $body = json_decode((string)$request->getBody(), true);
        $this->assertSame(1, $body['x']);
    }
}
