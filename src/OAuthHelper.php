<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient;

use Softlivery\WhatsappCloudApiClient\Http\HttpClient;
use Softlivery\WhatsappCloudApiClient\Http\Middleware\ErrorRaisingClient;
use Softlivery\WhatsappCloudApiClient\Request\RequestFactory;
use Softlivery\WhatsappCloudApiClient\Response\AssignedUsersApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\AssignPartnerApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\AssignUserApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\CodeExchangeApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\GenericApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\GetPhoneNumberApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\SubscribeAppApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\TemplatesApiResponse;
use Softlivery\WhatsappCloudApiClient\Response\WabaApiResponse;

class OAuthHelper
{
    private string $clientId;
    private string $clientSecret;
    private HttpClient $httpClient;

    public function __construct(string $clientId, string $clientSecret, HttpClient $httpClient)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient = new ErrorRaisingClient($httpClient);
    }

    public function exchangeCode(string $code, string $redirectUrl): CodeExchangeApiResponse
    {
        $request = RequestFactory::exchangeCode($this->clientId, $this->clientSecret, $code, $redirectUrl);
        $response = $this->httpClient->send($request);
        return new CodeExchangeApiResponse($response);
    }

    public function assignedUsers(string $businessId, string $accessToken, string $wabaId): AssignedUsersApiResponse
    {
        $request = RequestFactory::assignedUsers($wabaId, $businessId, $accessToken);
        $response = $this->httpClient->send($request);
        return new AssignedUsersApiResponse($response);
    }

    public function assignUser(string $userId, string $accessToken, string $wabaId): AssignUserApiResponse
    {
        $request = RequestFactory::assignUser($wabaId, $userId, $accessToken);
        $response = $this->httpClient->send($request);
        return new AssignUserApiResponse($response);
    }

    public function assignPartner(string $partnerId, string $accessToken, string $wabaId): AssignPartnerApiResponse
    {
        $request = RequestFactory::assignPartner($wabaId, $partnerId, $accessToken);
        $response = $this->httpClient->send($request);
        return new AssignPartnerApiResponse($response);
    }

    public function subscribeApp(string $wabaId, string $accessToken): SubscribeAppApiResponse
    {
        $request = RequestFactory::subscribeApp($wabaId, $accessToken);
        $response = $this->httpClient->send($request);
        return new SubscribeAppApiResponse($response);
    }

    public function getPhoneNumber(string $phoneNumberId, string $accessToken): GetPhoneNumberApiResponse
    {
        $request = RequestFactory::getPhoneNumber($phoneNumberId, $accessToken);
        $response = $this->httpClient->send($request);
        return new GetPhoneNumberApiResponse($response);
    }

    public function getPhoneNumbers(string $wabaId): WabaApiResponse
    {
        $request = RequestFactory::getPhoneNumbers($wabaId);
        $response = $this->httpClient->send($request);
        return new WabaApiResponse($response);
    }

    public function getWaba(string $wabaId, array $fields = []): WabaApiResponse
    {
        $request = RequestFactory::getWaba($wabaId, $fields);
        $response = $this->httpClient->send($request);
        return new WabaApiResponse($response);
    }

    public function getConversationAnalytics(string $wabaId, array $params = []): GenericApiResponse
    {
        $request = RequestFactory::getConversationAnalytics($wabaId, $params);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function getMessageTemplates(string $wabaId, array $params = []): TemplatesApiResponse
    {
        $request = RequestFactory::getMessageTemplates($wabaId, $params);
        $response = $this->httpClient->send($request);
        return new TemplatesApiResponse($response);
    }

    public function createMessageTemplate(string $wabaId, array $template): GenericApiResponse
    {
        $request = RequestFactory::createMessageTemplate($wabaId, $template);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function deleteMessageTemplate(string $wabaId, string $name, array $extraQuery = []): GenericApiResponse
    {
        $request = RequestFactory::deleteMessageTemplate($wabaId, $name, $extraQuery);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function getTemplateAnalytics(string $wabaId, array $params = []): GenericApiResponse
    {
        $request = RequestFactory::getTemplateAnalytics($wabaId, $params);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function getBusinessProfile(string $phoneNumberId, array $fields = []): GenericApiResponse
    {
        $request = RequestFactory::getBusinessProfile($phoneNumberId, $fields);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function updateBusinessProfile(string $phoneNumberId, array $profile): GenericApiResponse
    {
        $request = RequestFactory::updateBusinessProfile($phoneNumberId, $profile);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }

    public function unsubscribeApp(string $wabaId): GenericApiResponse
    {
        $request = RequestFactory::unsubscribeApp($wabaId);
        $response = $this->httpClient->send($request);
        return new GenericApiResponse($response);
    }
}
