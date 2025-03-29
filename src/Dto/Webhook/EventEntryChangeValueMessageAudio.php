<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageAudio
{
    public string $mime_type;
    public string $sha256;
    public string $id;
    public bool $voice;
}
