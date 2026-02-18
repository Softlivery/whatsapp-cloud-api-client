<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http\Middleware;

use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Exception\GraphApiException;
use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

/**
 * Throws on HTTP error status or error payload.
 */
final class ErrorRaisingClient implements HttpClient
{
    public function __construct(private readonly HttpClient $inner)
    {
    }

    /**
     * @throws ApiResponseException
     */
    public function send(ApiRequest $request): HttpResponse
    {
        $response = $this->inner->send($request);

        if ($response->isError()) {
            $payload = $response->getDecodedBody();
            $status = $response->getStatusCode() ?? 0;
            $message = $this->extractMessage($payload) ?? 'Unknown API error';

            if (is_array($payload) && isset($payload['error']) && is_array($payload['error'])) {
                $error = $payload['error'];
                throw new GraphApiException(
                    sprintf('[%d] %s', $status, $message),
                    $status,
                    isset($error['code']) && is_int($error['code']) ? $error['code'] : null,
                    isset($error['error_subcode']) && is_int($error['error_subcode']) ? $error['error_subcode'] : null,
                    isset($error['type']) && is_string($error['type']) ? $error['type'] : null,
                    isset($error['fbtrace_id']) && is_string($error['fbtrace_id']) ? $error['fbtrace_id'] : null,
                    $payload
                );
            }

            throw new ApiResponseException(sprintf('[%d] %s', $status, $message));
        }

        return $response;
    }

    private function extractMessage(mixed $payload): ?string
    {
        if (!is_array($payload)) {
            return null;
        }
        // Common formats:
        // { "error": { "message": "...", "code": 123, ... } }
        if (isset($payload['error'])) {
            $err = $payload['error'];
            if (is_array($err) && isset($err['message']) && is_string($err['message'])) {
                return $err['message'];
            }
            if (is_string($err)) {
                return $err;
            }
        }
        // Fallback keys
        foreach (['message', 'error_message', 'detail'] as $k) {
            if (isset($payload[$k]) && is_string($payload[$k])) {
                return $payload[$k];
            }
        }
        return null;
    }
}
