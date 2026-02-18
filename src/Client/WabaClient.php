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

    public function subscribeApp(string $accessToken): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::subscribeApp($this->wabaId, $accessToken));
        return new GenericApiResponse($response);
    }

    public function unsubscribeApp(): GenericApiResponse
    {
        $response = $this->sendRequest(RequestFactory::unsubscribeApp($this->wabaId));
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
