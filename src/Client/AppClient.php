<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\GenericApiResponse;

/**
 * Operations scoped to a Meta App rather than a WABA.
 *
 * Uses an App access token (`{appId}|{appSecret}`) — accepted both as a Bearer
 * header and as the `access_token` query parameter on each request.
 */
final class AppClient extends BaseClient
{
    public function __construct(
        string $accessToken,
        private readonly string $appId,
        HttpClient $httpClient,
    ) {
        parent::__construct($accessToken, $httpClient);
    }

    /**
     * Replaces the App's webhook subscription for a given object (e.g.
     * `whatsapp_business_account`). Idempotent — Meta replaces the prior
     * configuration with the supplied fields.
     *
     * @param string[] $fields
     */
    public function setSubscription(
        string $object,
        array $fields,
        string $callbackUrl,
        string $verifyToken,
    ): GenericApiResponse {
        $response = $this->sendRequest(
            RequestFactory::setAppSubscription(
                $this->appId,
                $object,
                $fields,
                $callbackUrl,
                $verifyToken,
                $this->accessToken,
            ),
        );
        return new GenericApiResponse($response);
    }
}
