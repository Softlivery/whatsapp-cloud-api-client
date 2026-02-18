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

    public function subscribeApp(string $wabaId, string $accessToken): SubscribeAppApiResponse
    {
        $request = RequestFactory::subscribeApp($wabaId, $accessToken);
        $response = $this->httpClient->send($request);
        return new SubscribeAppApiResponse($response);
    }

    public function getPhoneNumber(string $phonenumber_id, string $accessToken)
    {
        $request = RequestFactory::getPhoneNumber($phonenumber_id, $accessToken);
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
