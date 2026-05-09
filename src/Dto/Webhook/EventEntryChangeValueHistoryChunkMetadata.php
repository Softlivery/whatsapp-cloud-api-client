<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

/**
 * Metadata of a single chunk inside a `history` webhook payload.
 *
 *  - `phase`        — sync phase (0 onwards). 0 is the initial onboarding sync.
 *  - `chunk_order`  — 1-based index of this chunk inside the phase.
 *  - `progress`     — 0..100 percentage of the phase completed at this chunk.
 */
class EventEntryChangeValueHistoryChunkMetadata
{
    public ?int $phase = null;
    public ?int $chunk_order = null;
    public ?int $progress = null;
}
