<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * One chunk inside a `history` webhook batch. Meta delivers the WA
 * Business App backfill in chunks per phase; each chunk carries
 * progress metadata and a list of conversation threads.
 */
class EventEntryChangeValueHistoryChunk
{
    public ?EventEntryChangeValueHistoryChunkMetadata $metadata = null;
    /** @var EventEntryChangeValueHistoryThread[] */
    public array $threads = [];
}
