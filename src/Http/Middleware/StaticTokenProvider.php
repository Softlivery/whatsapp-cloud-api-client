<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http\Middleware;

final class StaticTokenProvider implements TokenProvider
{
    public function __construct(private readonly ?string $token)
    {
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
