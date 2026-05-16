<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueUserIdUpdate;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueUserIdUpdateChange;
use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;

class WebhookUserIdUpdateEventTest extends TestCase
{
    public function testParsesUserIdUpdateEvent(): void
    {
        $secret = 'secret';
        $payload = json_encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => 'waba-id',
                'changes' => [[
                    'field' => 'user_id_update',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '15550783881',
                            'phone_number_id' => '106540352242922',
                        ],
                        'contacts' => [[
                            'profile' => ['name' => 'Pablo M.'],
                            'wa_id' => '16505551234',
                        ]],
                        'user_id_update' => [[
                            'wa_id' => '16505551234',
                            'detail' => 'User id for Pablo M. has been updated',
                            'user_id' => [
                                'previous' => 'US.13491208655302741918',
                                'current' => 'US.99991208655302741999',
                            ],
                            'parent_user_id' => [
                                'previous' => 'US.ENT.11815799212886844830',
                                'current' => 'US.ENT.99915799212886844999',
                            ],
                            'timestamp' => '1750030073',
                        ]],
                    ],
                ]],
            ]],
        ]);

        $helper = new WebhookEventHelper('verify', $secret);
        $signature = hash_hmac('sha256', (string)$payload, $secret);

        $event = $helper->validateAndParse((string)$payload, [
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=' . $signature,
        ]);

        $value = $event->entry[0]->changes[0]->value;
        $this->assertSame('user_id_update', $value->type());
        $this->assertNotNull($value->user_id_update);
        $this->assertCount(1, $value->user_id_update);

        $update = $value->user_id_update[0];
        $this->assertInstanceOf(EventEntryChangeValueUserIdUpdate::class, $update);
        $this->assertSame('16505551234', $update->wa_id);
        $this->assertSame('User id for Pablo M. has been updated', $update->detail);
        $this->assertSame('1750030073', $update->timestamp);

        $this->assertInstanceOf(EventEntryChangeValueUserIdUpdateChange::class, $update->user_id);
        $this->assertSame('US.13491208655302741918', $update->user_id->previous);
        $this->assertSame('US.99991208655302741999', $update->user_id->current);

        $this->assertInstanceOf(EventEntryChangeValueUserIdUpdateChange::class, $update->parent_user_id);
        $this->assertSame('US.ENT.11815799212886844830', $update->parent_user_id->previous);
        $this->assertSame('US.ENT.99915799212886844999', $update->parent_user_id->current);
    }

    public function testParsesUserIdUpdateEventWithoutParentBsuid(): void
    {
        $secret = 'secret';
        $payload = json_encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => 'waba-id',
                'changes' => [[
                    'field' => 'user_id_update',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '15550783881',
                            'phone_number_id' => '106540352242922',
                        ],
                        'user_id_update' => [[
                            'wa_id' => '16505551234',
                            'detail' => 'User id changed',
                            'user_id' => [
                                'previous' => 'US.13491208655302741918',
                                'current' => 'US.99991208655302741999',
                            ],
                            'timestamp' => '1750030073',
                        ]],
                    ],
                ]],
            ]],
        ]);

        $helper = new WebhookEventHelper('verify', $secret);
        $signature = hash_hmac('sha256', (string)$payload, $secret);

        $event = $helper->validateAndParse((string)$payload, [
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=' . $signature,
        ]);

        $value = $event->entry[0]->changes[0]->value;
        $this->assertSame('user_id_update', $value->type());
        $update = $value->user_id_update[0];
        $this->assertSame('US.99991208655302741999', $update->user_id->current);
        $this->assertNull($update->parent_user_id);
    }
}
