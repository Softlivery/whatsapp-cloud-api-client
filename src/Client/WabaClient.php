<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Client;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\GenericApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\WabaApiResponse;

final class WabaClient extends BaseClient
{
    public function __construct(string $accessToken, private readonly string $wabaId, HttpClient $httpClient)
    {
        parent::__construct($accessToken, $httpClient);
    }

    /** @param string[] $fields */
    public function getWaba(array $fields = []): WabaApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getWaba($this->wabaId, $fields));
        return new WabaApiResponse($response);
    }

    public function phoneNumbers(): WabaApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getPhoneNumbers($this->wabaId));
        return new WabaApiResponse($response);
    }

    /** @param array<string,mixed> $params */
    public function conversationAnalytics(array $params = []): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getConversationAnalytics($this->wabaId, $params));
        return new GenericApiResponse($response);
    }

    public function subscribeApp(
        string $accessToken,
        ?string $overrideCallbackUri = null,
        ?string $verifyToken = null
    ): GenericApiResponse {
        $response = $this->sendRequest(
            RequestFactory::subscribeApp($this->wabaId, $accessToken, $overrideCallbackUri, $verifyToken)
        );
        return new GenericApiResponse($response);
    }

    public function unsubscribeApp(): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::unsubscribeApp($this->wabaId));
        return new GenericApiResponse($response);
    }

    /**
     * Triggers a one-shot Coexistence sync (contacts or chat history) for a
     * paired WhatsApp Business app phone number. The `$accessToken` must be the
     * customer access token returned by the Embedded Signup code exchange, not
     * a System User token. Response carries a `request_id` that should be
     * persisted for support purposes; webhooks then arrive asynchronously.
     *
     * `$syncType`: SmbAppDataSyncType::SMB_APP_STATE_SYNC | ::HISTORY
     */
    public function syncSmbAppData(string $phoneNumberId, string $syncType, string $accessToken): GenericApiResponse
    {
        $response = $this->sendRequest(
            RequestFactory::syncSmbAppData($phoneNumberId, $syncType, $accessToken)
        );
        return new GenericApiResponse($response);
    }

    public function assignedUsers(string $businessId): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::assignedUsers($this->wabaId, $businessId, $this->accessToken));
        return new GenericApiResponse($response);
    }

    public function assignUser(string $userId): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::assignUser($this->wabaId, $userId, $this->accessToken));
        return new GenericApiResponse($response);
    }

    public function assignPartner(string $partnerId): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::assignPartner($this->wabaId, $partnerId, $this->accessToken));
        return new GenericApiResponse($response);
    }

    /** @param string[] $fields */
    public function businessProfile(string $phoneNumberId, array $fields = []): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getBusinessProfile($phoneNumberId, $fields));
        return new GenericApiResponse($response);
    }

    /** @param array<string,mixed> $profile */
    public function updateBusinessProfile(string $phoneNumberId, array $profile): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::updateBusinessProfile($phoneNumberId, $profile));
        return new GenericApiResponse($response);
    }

    /** @param array<string,mixed> $params */
    public function groups(string $phoneNumberId, array $params = []): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::getGroups($phoneNumberId, $params));
        return new GenericApiResponse($response);
    }
}
