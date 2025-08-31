<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

abstract class ApiResponse
{
    protected HttpResponse $httpResponse;
    /** @var array<string,mixed> */
    protected array $decodedBody;

    public function __construct(HttpResponse $response)
    {
        $this->httpResponse = $response;
        $body = $response->getDecodedBody();
        $this->decodedBody = is_array($body) ? $body : [];
    }

    public function getStatusCode(): int
    {
        return $this->httpResponse->getStatusCode();
    }

    /**
     * Returns the first header value for a given header name if exists, null otherwise.
     */
    public function getHeader(string $name): ?string
    {
        $headers = $this->httpResponse->getHeaders();
        $normalized = strtolower($name);
        foreach ($headers as $key => $values) {
            if (strtolower($key) === $normalized) {
                if (is_array($values)) {
                    return $values[0] ?? null;
                }
                return is_string($values) ? $values : null;
            }
        }
        return null;
    }

    /**
     * Returns all headers as an associative array.
     */
    public function getHeaders(): array
    {
        return $this->httpResponse->getHeaders();
    }

    /**
     * The decoded JSON body as an associative array (or empty array if not applicable).
     *
     * @return array<string,mixed>
     */
    public function json(): array
    {
        return $this->decodedBody;
    }

    /**
     * Safe accessor for array fields.
     *
     * @return array<int|string,mixed>
     */
    protected function getArray(string $key, array $default = []): array
    {
        $val = $this->decodedBody[$key] ?? $default;
        return is_array($val) ? $val : $default;
    }

    protected function getString(string $key, ?string $default = null): ?string
    {
        $val = $this->decodedBody[$key] ?? $default;
        return is_string($val) ? $val : $default;
    }

    protected function getInt(string $key, ?int $default = null): ?int
    {
        $val = $this->decodedBody[$key] ?? $default;
        return is_int($val) ? $val : $default;
    }

    protected function getBool(string $key, ?bool $default = null): ?bool
    {
        $val = $this->decodedBody[$key] ?? $default;
        return is_bool($val) ? $val : $default;
    }
}
