<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * One conversation thread inside a history chunk. `id` is the historical
 * thread identifier (typically the WhatsApp user phone number, may be
 * omitted under the usernames feature). `context` carries the user's
 * identifiers, `messages` the historical messages of the thread.
 */
class EventEntryChangeValueHistoryThread
{
    public ?string $id = null;
    public ?EventEntryChangeValueHistoryThreadContext $context = null;
    /** @var EventEntryChangeValueHistoryMessage[] */
    public array $messages = [];
}
