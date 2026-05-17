<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueHistoryChunk;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueHistoryMessage;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueHistoryThread;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueMessageEcho;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueStateSyncContact;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueStateSyncEntry;
use Softlivery\WhatsappCloudApiClient\Dto\Webhook\EventEntryChangeValueStateSyncMetadata;
use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;

class WebhookCoexistenceEventsTest extends TestCase
{
    private const SECRET = 'test-secret';

    // Fictional identifiers — never use real WABA / phone / BSUID values in
    // tests. The numeric strings are clearly placeholders, not E.164 numbers
    // tied to any actual WhatsApp account.
    private const WABA_ID         = '111111111111111';
    private const PHONE_NUMBER_ID = '222222222222222';
    private const BUSINESS_PHONE  = '15550000000';
    private const CUSTOMER_PHONE  = '15550000001';
    private const CUSTOMER_BSUID  = 'BSUID-CUSTOMER-1';

    public function testParsesSmbMessageEchoTextEvent(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => self::BUSINESS_PHONE,
                            'phone_number_id' => self::PHONE_NUMBER_ID,
                        ],
                        'contacts' => [[
                            'wa_id' => self::CUSTOMER_PHONE,
                            'user_id' => self::CUSTOMER_BSUID,
                        ]],
                        'message_echoes' => [[
                            'from' => self::BUSINESS_PHONE,
                            'to' => self::CUSTOMER_PHONE,
                            'to_user_id' => self::CUSTOMER_BSUID,
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
        self::assertSame(self::BUSINESS_PHONE, $echo->from);
        self::assertSame(self::CUSTOMER_PHONE, $echo->to);
        self::assertSame(self::CUSTOMER_BSUID, $echo->to_user_id);
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
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => self::BUSINESS_PHONE,
                            'phone_number_id' => self::PHONE_NUMBER_ID,
                        ],
                        'contacts' => [[
                            'wa_id' => self::CUSTOMER_PHONE,
                        ]],
                        'message_echoes' => [[
                            'from' => self::BUSINESS_PHONE,
                            'to' => self::CUSTOMER_PHONE,
                            'id' => 'wamid.test-echo-image',
                            'timestamp' => '1700000100',
                            'type' => 'image',
                            'image' => [
                                'caption' => 'fictional image caption',
                                'mime_type' => 'image/jpeg',
                                'sha256' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                                'id' => '333333333333333',
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

    public function testParsesHistoryWebhookWithThreadsAndMessages(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'history',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => self::BUSINESS_PHONE,
                            'phone_number_id' => self::PHONE_NUMBER_ID,
                        ],
                        'history' => [[
                            'metadata' => [
                                'phase' => 0,
                                'chunk_order' => 1,
                                'progress' => 100,
                            ],
                            'threads' => [[
                                'id' => self::CUSTOMER_PHONE,
                                'context' => [
                                    'wa_id' => self::CUSTOMER_PHONE,
                                    'user_id' => self::CUSTOMER_BSUID,
                                ],
                                'messages' => [[
                                    'from' => self::CUSTOMER_PHONE,
                                    'from_user_id' => self::CUSTOMER_BSUID,
                                    'id' => 'wamid.test-history-1',
                                    'timestamp' => '1700000200',
                                    'type' => 'text',
                                    'text' => ['body' => 'fictional historic message'],
                                    'history_context' => ['status' => 'delivered'],
                                ]],
                            ]],
                        ]],
                    ],
                ]],
            ]],
        ]);

        $event = $this->parse($payload);
        $value = $event->entry[0]->changes[0]->value;

        self::assertSame('history', $value->type());
        self::assertIsArray($value->history);
        self::assertCount(1, $value->history);

        $chunk = $value->history[0];
        self::assertInstanceOf(EventEntryChangeValueHistoryChunk::class, $chunk);
        self::assertNotNull($chunk->metadata);
        self::assertSame(0, $chunk->metadata->phase);
        self::assertSame(1, $chunk->metadata->chunk_order);
        self::assertSame(100, $chunk->metadata->progress);

        self::assertCount(1, $chunk->threads);
        $thread = $chunk->threads[0];
        self::assertInstanceOf(EventEntryChangeValueHistoryThread::class, $thread);
        self::assertSame(self::CUSTOMER_PHONE, $thread->id);
        self::assertNotNull($thread->context);
        self::assertSame(self::CUSTOMER_PHONE, $thread->context->wa_id);
        self::assertSame(self::CUSTOMER_BSUID, $thread->context->user_id);

        self::assertCount(1, $thread->messages);
        $message = $thread->messages[0];
        self::assertInstanceOf(EventEntryChangeValueHistoryMessage::class, $message);
        self::assertSame(self::CUSTOMER_PHONE, $message->from);
        self::assertSame(self::CUSTOMER_BSUID, $message->from_user_id);
        self::assertSame('text', $message->type);
        self::assertSame('fictional historic message', $message->text->body);
        self::assertNotNull($message->history_context);
        self::assertSame('delivered', $message->history_context->status);
    }

    public function testParsesSmbAppStateSyncContactBatch(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'smb_app_state_sync',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => self::BUSINESS_PHONE,
                            'phone_number_id' => self::PHONE_NUMBER_ID,
                        ],
                        'state_sync' => [
                            [
                                'type' => 'contact',
                                'contact' => [
                                    'full_name' => 'Fictional Customer One',
                                    'first_name' => 'Fictional',
                                    'phone_number' => self::CUSTOMER_PHONE,
                                    'user_id' => self::CUSTOMER_BSUID,
                                ],
                                'action' => 'add',
                                'metadata' => [
                                    'timestamp' => '1700000000000',
                                    'version' => 1,
                                ],
                            ],
                            [
                                'type' => 'contact',
                                'contact' => [
                                    'full_name' => 'Fictional Customer Two',
                                    'phone_number' => '15550000002',
                                    'user_id' => 'BSUID-CUSTOMER-2',
                                ],
                                'action' => 'update',
                                'metadata' => [
                                    'timestamp' => '1700000001000',
                                    'version' => 1,
                                ],
                            ],
                            [
                                'type' => 'contact',
                                'contact' => [
                                    'phone_number' => '15550000003',
                                    'user_id' => 'BSUID-CUSTOMER-3',
                                ],
                                'action' => 'remove',
                                'metadata' => [
                                    'timestamp' => '1700000002000',
                                    'version' => 1,
                                ],
                            ],
                        ],
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('smb_app_state_sync', $value->type());
        self::assertIsArray($value->state_sync);
        self::assertCount(3, $value->state_sync);

        $first = $value->state_sync[0];
        self::assertInstanceOf(EventEntryChangeValueStateSyncEntry::class, $first);
        self::assertSame('contact', $first->type);
        self::assertSame('add', $first->action);
        self::assertInstanceOf(EventEntryChangeValueStateSyncContact::class, $first->contact);
        self::assertSame('Fictional Customer One', $first->contact->full_name);
        self::assertSame('Fictional', $first->contact->first_name);
        self::assertSame(self::CUSTOMER_PHONE, $first->contact->phone_number);
        self::assertSame(self::CUSTOMER_BSUID, $first->contact->user_id);
        self::assertInstanceOf(EventEntryChangeValueStateSyncMetadata::class, $first->metadata);
        self::assertSame('1700000000000', $first->metadata->timestamp);
        self::assertSame(1, $first->metadata->version);

        self::assertSame('update', $value->state_sync[1]->action);
        self::assertNull($value->state_sync[1]->contact->first_name);

        self::assertSame('remove', $value->state_sync[2]->action);
        self::assertNull($value->state_sync[2]->contact->full_name);
    }

    public function testParsesPhoneNumberQualityUpdate(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'phone_number_quality_update',
                    'value' => [
                        'display_phone_number' => self::BUSINESS_PHONE,
                        'event' => 'FLAGGED',
                        'current_limit' => 'TIER_1000',
                        'old_quality_score' => 'GREEN',
                        'new_quality_score' => 'YELLOW',
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('phone_number_quality_update', $value->type());
        self::assertSame(self::BUSINESS_PHONE, $value->display_phone_number);
        self::assertSame('FLAGGED', $value->event);
        self::assertSame('TIER_1000', $value->current_limit);
        self::assertSame('GREEN', $value->old_quality_score);
        self::assertSame('YELLOW', $value->new_quality_score);
    }

    public function testParsesAccountReviewUpdate(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'account_review_update',
                    'value' => [
                        'decision' => 'APPROVED',
                        'reason' => 'fictional review reason',
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('account_review_update', $value->type());
        self::assertSame('APPROVED', $value->decision);
        self::assertSame('fictional review reason', $value->reason);
    }

    public function testParsesAccountAlerts(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'account_alerts',
                    'value' => [
                        'alert_severity' => 'HIGH',
                        'alert_type' => 'POLICY_VIOLATION',
                        'entity_type' => 'WABA',
                        'entity_id' => self::WABA_ID,
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('account_alerts', $value->type());
        self::assertSame('HIGH', $value->alert_severity);
        self::assertSame('POLICY_VIOLATION', $value->alert_type);
        self::assertSame('WABA', $value->entity_type);
        self::assertSame(self::WABA_ID, $value->entity_id);
    }

    public function testParsesBusinessCapabilityUpdate(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'business_capability_update',
                    'value' => [
                        'max_phone_numbers_per_business' => 25,
                        'max_daily_conversation_per_phone' => 1000,
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('business_capability_update', $value->type());
        self::assertSame(25, $value->max_phone_numbers_per_business);
        self::assertSame(1000, $value->max_daily_conversation_per_phone);
    }

    public function testParsesMessageTemplateQualityUpdate(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'message_template_quality_update',
                    'value' => [
                        'message_template_id' => 99999,
                        'message_template_name' => 'fictional_template',
                        'message_template_language' => 'en_US',
                        'previous_quality_score' => 'GREEN',
                        'new_quality_score' => 'YELLOW',
                    ],
                ]],
            ]],
        ]);

        $value = $this->parse($payload)->entry[0]->changes[0]->value;
        self::assertSame('message_template_quality_update', $value->type());
        self::assertSame(99999, $value->message_template_id);
        self::assertSame('YELLOW', $value->new_quality_score);
    }

    public function testContactProfileIsOptionalForCoexistenceContacts(): void
    {
        $payload = $this->encode([
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => self::WABA_ID,
                'changes' => [[
                    'field' => 'smb_message_echoes',
                    'value' => [
                        'metadata' => [
                            'display_phone_number' => self::BUSINESS_PHONE,
                            'phone_number_id' => self::PHONE_NUMBER_ID,
                        ],
                        'contacts' => [[
                            'wa_id' => self::CUSTOMER_PHONE,
                            'user_id' => self::CUSTOMER_BSUID,
                        ]],
                        'message_echoes' => [[
                            'from' => self::BUSINESS_PHONE,
                            'to' => self::CUSTOMER_PHONE,
                            'id' => 'wamid.test-no-profile',
                            'timestamp' => '1700000300',
                            'type' => 'text',
                            'text' => ['body' => 'fictional body'],
                        ]],
                    ],
                ]],
            ]],
        ]);

        $event = $this->parse($payload);
        $contact = $event->entry[0]->changes[0]->value->contacts[0];

        self::assertSame(self::CUSTOMER_PHONE, $contact->wa_id);
        self::assertSame(self::CUSTOMER_BSUID, $contact->user_id);
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
