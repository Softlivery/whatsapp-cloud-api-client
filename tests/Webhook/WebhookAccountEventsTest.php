<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;

class WebhookAccountEventsTest extends TestCase
{
    public function testParsesAccountOffboardedEvent(): void
    {
        $secret = 'secret';
        $payload = json_encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => 'waba-id',
                'changes' => [[
                    'field' => 'account_update',
                    'value' => [
                        'event' => 'account_offboarded',
                        'account_offboarded' => [
                            'waba_id' => 'waba-id',
                            'phone_number_id' => 'phone-id',
                            'timestamp' => '1730000000',
                        ],
                    ],
                ]],
            ]],
        ]);

        $helper = new WebhookEventHelper('verify', $secret);
        $signature = hash_hmac('sha256', (string)$payload, $secret);

        $event = $helper->validateAndParse((string)$payload, [
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=' . $signature,
        ]);

        $this->assertSame('event', $event->entry[0]->changes[0]->value->type());
        $this->assertTrue($event->entry[0]->changes[0]->value->isAccountOffboarded());
    }

    public function testParsesAccountReconnectedEvent(): void
    {
        $secret = 'secret';
        $payload = json_encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => 'waba-id',
                'changes' => [[
                    'field' => 'account_update',
                    'value' => [
                        'event' => 'account_reconnected',
                        'account_reconnected' => [
                            'waba_id' => 'waba-id',
                            'phone_number_id' => 'phone-id',
                            'timestamp' => '1730001000',
                        ],
                    ],
                ]],
            ]],
        ]);

        $helper = new WebhookEventHelper('verify', $secret);
        $signature = hash_hmac('sha256', (string)$payload, $secret);

        $event = $helper->validateAndParse((string)$payload, [
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=' . $signature,
        ]);

        $this->assertSame('event', $event->entry[0]->changes[0]->value->type());
        $this->assertTrue($event->entry[0]->changes[0]->value->isAccountReconnected());
    }
}
