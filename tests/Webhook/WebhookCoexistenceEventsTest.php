<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueMessageEcho;
use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;

class WebhookCoexistenceEventsTest extends TestCase
{
    private const SECRET = 'secret';

    public function testParsesSmbMessageEchoTextEvent(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => '111111111111111',
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '15550000000',
                            'phone_number_id' => '222222222222222',
                        ],
                        'contacts' => [[
                            'wa_id' => '15550000001',
                            'user_id' => 'BSUID-CUSTOMER-1',
                        ]],
                        'message_echoes' => [[
                            'from' => '15550000000',
                            'to' => '15550000001',
                            'to_user_id' => 'BSUID-CUSTOMER-1',
                            'id' => 'wamid.test-echo-text',
                            'timestamp' => '1700000000',
                            'type' => 'text',
                            'text' => ['body' => 'fictional echo body'],
                        ]],
                    ],
                ]],
            ]],
        ]);

        $event = $this->parse($payload);
        $value = $event->entry[0]->changes[0]->value;

        self::assertSame('smb_message_echoes', $value->type());
        self::assertSame('smb_message_echoes', $event->entry[0]->changes[0]->field);
        self::assertNull($value->messages);
        self::assertNull($value->statuses);
        self::assertIsArray($value->message_echoes);
        self::assertCount(1, $value->message_echoes);

        $echo = $value->message_echoes[0];
        self::assertInstanceOf(EventEntryChangeValueMessageEcho::class, $echo);
        self::assertSame('15550000000', $echo->from);
        self::assertSame('15550000001', $echo->to);
        self::assertSame('BSUID-CUSTOMER-1', $echo->to_user_id);
        self::assertSame('wamid.test-echo-text', $echo->id);
        self::assertSame('1700000000', $echo->timestamp);
        self::assertSame('text', $echo->type);
        self::assertNotNull($echo->text);
        self::assertSame('fictional echo body', $echo->text->body);
    }

    public function testParsesSmbMessageEchoImageEvent(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => '111111111111111',
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '15550000000',
                            'phone_number_id' => '222222222222222',
                        ],
                        'contacts' => [[
                            'wa_id' => '15550000001',
                        ]],
                        'message_echoes' => [[
                            'from' => '15550000000',
                            'to' => '15550000001',
                            'id' => 'wamid.test-echo-image',
                            'timestamp' => '1700000100',
                            'type' => 'image',
                            'image' => [
                                'caption' => 'fictional image caption',
                                'mime_type' => 'image/jpeg',
                                'sha256' => 'abc123',
                                'id' => '999000111',
                            ],
                        ]],
                    ],
                ]],
            ]],
        ]);

        $event = $this->parse($payload);
        $echo = $event->entry[0]->changes[0]->value->message_echoes[0];

        self::assertSame('image', $echo->type);
        self::assertNotNull($echo->image);
        self::assertSame('fictional image caption', $echo->image->caption);
        self::assertSame('image/jpeg', $echo->image->mime_type);
    }

    public function testContactProfileIsOptionalForCoexistenceContacts(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => 'waba',
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'metadata' => [
                            'display_phone_number' => '+1',
                            'phone_number_id' => 'phone',
                        ],
                        'contacts' => [[
                            'wa_id' => '15550000001',
                            'user_id' => 'BSUID-1',
                        ]],
                        'message_echoes' => [[
                            'from' => '1',
                            'to' => '15550000001',
                            'id' => 'wamid.x',
                            'timestamp' => '1',
                            'type' => 'text',
                            'text' => ['body' => 'hi'],
                        ]],
                    ],
                ]],
            ]],
        ]);

        $event = $this->parse($payload);
        $contact = $event->entry[0]->changes[0]->value->contacts[0];

        self::assertSame('15550000001', $contact->wa_id);
        self::assertSame('BSUID-1', $contact->user_id);
        self::assertNull($contact->profile);
    }

    /** @param array<string,mixed> $data */
    private function encode(array $data): string
    {
        return (string) json_encode($data);
    }

    private function parse(string $payload): \Softlivery\WhatsappCloudApiClient\Dto\Webhook\Event
    {
        $helper = new WebhookEventHelper('verify', self::SECRET);
        $signature = hash_hmac('sha256', $payload, self::SECRET);
        return $helper->validateAndParse($payload, [
            WebhookEventHelper::HTTP_X_HUB_SIGNATURE_256 => 'sha256=' . $signature,
        ]);
    }
}
