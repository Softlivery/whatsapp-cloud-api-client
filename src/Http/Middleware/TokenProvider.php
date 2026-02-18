<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http\Middleware;

interface TokenProvider
{
    public function getToken(): ?string;
}
