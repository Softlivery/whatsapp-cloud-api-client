<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageReferral
{
    public string $source_url;
    public string $source_type;
    public string $source_id;
    public string $headline;
    public string $body;
    public string $media_type;
    public ?string $image_url;
    public ?string $video_url;
    public ?string $thumbnail_url;
    public string $ctwa_clid;
}
