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
    /**
     * Populated only on the chat-history-sharing-declined payload, where Meta
     * sends a single chunk carrying an error (code 2593109) instead of
     * metadata + threads. Each entry is the raw error object as Meta sends it.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $errors = [];
}
