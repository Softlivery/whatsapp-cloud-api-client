<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageAudio
{
    public ?string $mime_type = null;
    public ?string $sha256 = null;
    public ?string $id = null;
    public ?bool $voice = null;
    public ?string $url = null;
}
