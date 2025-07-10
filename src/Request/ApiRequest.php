<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

abstract class ApiRequest
{
    public array $headers = [];
    public string $method;
    public string $path;
    public int $timeout = 60;

    public function __construct(string $access_token, string $path, string $method, int $timeout = 60)
    {
        $this->path = $path;
        $this->method = $method;
        $this->timeout = $timeout;

        $this->headers = [
            'Authorization' => "Bearer $access_token",
        ];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getBody(): ?string
    {
        return null;
    }
}
