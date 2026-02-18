<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Config;

final class ApiConfig
{
    public function __construct(
        public readonly string $graphVersion = 'v25.0',
        public readonly int $defaultTimeout = 60
    ) {
    }
}
