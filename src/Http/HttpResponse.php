<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http;

class HttpResponse
{
    private string $body;
    private ?int $http_status_code;
    private array $headers;
    /**
     * @var array|mixed
     */
    private mixed $decoded_body;

    public function __construct(string $body, ?int $http_status_code = null, array $headers = [])
    {
        $this->body = $body;
        $this->http_status_code = $http_status_code;
        $this->headers = $headers;
        $this->decoded_body = json_decode($this->body, true) ?? [];
    }
    
    public function getStatusCode(): ?int
    {
        return $this->http_status_code;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getDecodedBody(): mixed
    {
        return $this->decoded_body;
    }

    public function isError(): bool
    {
        // Consider HTTP status code and presence of an "error" field in body
        if ($this->http_status_code !== null && $this->http_status_code >= 400) {
            return true;
        }
        return isset($this->decoded_body['error']);
    }
}
