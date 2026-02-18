<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Exception;

final class GraphApiException extends ApiResponseException
{
    /** @param array<string,mixed> $payload */
    public function __construct(
        string $message,
        private readonly int $httpStatus,
        private readonly ?int $graphCode = null,
        private readonly ?int $graphSubcode = null,
        private readonly ?string $graphType = null,
        private readonly ?string $fbtraceId = null,
        private readonly array $payload = []
    ) {
        parent::__construct($message, $this->graphCode ?? 0);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getGraphCode(): ?int
    {
        return $this->graphCode;
    }

    public function getGraphSubcode(): ?int
    {
        return $this->graphSubcode;
    }

    public function getGraphType(): ?string
    {
        return $this->graphType;
    }

    public function getFbtraceId(): ?string
    {
        return $this->fbtraceId;
    }

    /** @return array<string,mixed> */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
