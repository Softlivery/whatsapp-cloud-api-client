<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

final class MediaUploadApiResponse extends ApiResponse
{
    public function getMediaId(): ?string
    {
        return $this->getString('id');
    }
}
