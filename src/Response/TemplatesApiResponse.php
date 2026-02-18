<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

final class TemplatesApiResponse extends ApiResponse
{
    /** @return array<int,array<string,mixed>> */
    public function getData(): array
    {
        return $this->getArray('data', []);
    }

    public function getPaging(): array
    {
        return $this->getArray('paging', []);
    }
}
