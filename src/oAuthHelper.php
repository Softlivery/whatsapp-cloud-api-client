<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\ErrorRaisingClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\AssignedUsersApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\AssignPartnerApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\AssignUserApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\CodeExchangeApiResponse;

class oAuthHelper
{
    private string $client_id;
    private string $client_secret;
    private HttpClient $httpClient;

    public function __construct(string $client_id, string $client_secret, HttpClient $httpClient)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->httpClient = new ErrorRaisingClient($httpClient);
    }

    public function exchangeCode(string $code, string $redirect_url): CodeExchangeApiResponse
    {
        $request = RequestFactory::exchangeCode($this->client_id, $this->client_secret, $code, $redirect_url);
        $response = $this->httpClient->send($request);
        return new CodeExchangeApiResponse($response);
    }

    public function assignedUsers(string $business_id, string $access_token, string $waba_id): AssignedUsersApiResponse
    {
        $request = RequestFactory::assignedUsers($waba_id, $business_id, $access_token);
        $response = $this->httpClient->send($request);
        return new AssignedUsersApiResponse($response);
    }

    public function assignUser(string $user_id, string $access_token, string $waba_id): AssignUserApiResponse
    {
        $request = RequestFactory::assignUser($waba_id, $user_id, $access_token);
        $response = $this->httpClient->send($request);
        return new AssignUserApiResponse($response);
    }

    public function assignPartner(string $partner_id, string $access_token, string $waba_id): AssignPartnerApiResponse
    {
        $request = RequestFactory::assignPartner($waba_id, $partner_id, $access_token);
        $response = $this->httpClient->send($request);
        return new AssignPartnerApiResponse($response);
    }
}
