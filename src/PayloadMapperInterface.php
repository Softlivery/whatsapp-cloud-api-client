<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

interface PayloadMapperInterface
{
    /**
     * Maps the payload to a specified class.
     *
     * @param array $payload
     * @param string $targetClass
     * @return object
     */
    public function map(array $payload, string $targetClass): mixed;
}
