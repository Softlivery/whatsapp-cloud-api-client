<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

use JsonSerializable;

class ApiRequest
{
    private array $headers;
    private string $method;
    private string $path;
    private int $timeout;
    private array $query = [];
    private mixed $body = null; // array|string|\JsonSerializable|null

    public function __construct(string $path, string $method, int $timeout = 60)
    {
        $this->path = $path;
        $this->method = $method;
        $this->timeout = $timeout;

        $this->headers = [
            'Content-Type' => 'application/json',
        ];
    }

    // Fluent API

    public function withHeader(string $name, string $value): self
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    public function withHeaders(array $headers): self
    {
        $clone = clone $this;
        foreach ($headers as $k => $v) {
            $clone->headers[$k] = $v;
        }
        return $clone;
    }

    public function withQuery(array $params): self
    {
        $clone = clone $this;
        $clone->query = array_merge($clone->query, $params);
        return $clone;
    }

    public function withJsonBody(array|JsonSerializable $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        // ensure content type is present
        if (!isset($clone->headers['Content-Type'])) {
            $clone->headers['Content-Type'] = 'application/json';
        }
        return $clone;
    }

    public function withRawBody(?string $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    public function withTimeout(int $seconds): self
    {
        $clone = clone $this;
        $clone->timeout = $seconds;
        return $clone;
    }

    // Accessors expected by HttpClient

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Returns the path with query parameters appended (if any).
     */
    public function getPath(): string
    {
        if ($this->query === []) {
            return $this->path;
        }
        $query = http_build_query($this->query);
        if ($query === '') {
            return $this->path;
        }
        return rtrim($this->path, '?') . '?' . $query;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Returns the serialized body as string or null.
     */
    public function getBody(): ?string
    {
        if ($this->body === null) {
            return null;
        }
        if (is_string($this->body)) {
            return $this->body;
        }
        if ($this->body instanceof JsonSerializable) {
            return json_encode($this->body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (is_array($this->body)) {
            return json_encode($this->body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        // Fallback for unexpected types
        return (string)$this->body;
    }
}
