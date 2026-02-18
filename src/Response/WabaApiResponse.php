<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

final class WabaApiResponse extends ApiResponse
{
    /** @return array<int,array<string,mixed>> */
    public function getData(): array
    {
        return $this->getArray('data', []);
    }
}
