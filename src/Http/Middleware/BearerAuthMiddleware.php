<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http\Middleware;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;
use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

/**
 * Adds Authorization: Bearer <token> header if a token is available.
 */
final class BearerAuthMiddleware implements HttpClient
{
    public function __construct(
        private readonly HttpClient     $inner,
        private readonly ?TokenProvider $tokenProvider = null
    )
    {
    }

    public function send(ApiRequest $request): HttpResponse
    {
        $token = $this->tokenProvider?->getToken();
        if ($token) {
            $request = $request->withHeader('Authorization', "Bearer {$token}");
        }
        return $this->inner->send($request);
    }
}
