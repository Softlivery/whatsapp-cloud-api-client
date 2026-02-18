<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

use JsonSerializable;

final class RequestFactory
{
    /**
     * POST /{fromPhoneNumberId}/messages
     */
    public static function messageSend(string $fromPhoneNumberId, array|JsonSerializable $message, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("$fromPhoneNumberId/messages", 'POST', $timeout))
            ->withJsonBody($message);
    }

    /**
     * POST /{fromPhoneNumberId}/messages (mark as read)
     */
    public static function messageMarkAsRead(string $fromPhoneNumberId, string $messageId, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("$fromPhoneNumberId/messages", 'POST', $timeout))
            ->withJsonBody([
                'messaging_product' => 'whatsapp',
                'status' => 'read',
                'message_id' => $messageId,
            ]);
    }

    /**
     * POST /{wabaId}/assigned_partners?tasks=MANAGE&user={userId}&access_token={accessToken}
     */
    public static function assignPartner(string $wabaId, string $partnerId, string $accessToken, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/assigned_partners", 'POST', $timeout))
            ->withQuery([
                'partner' => $partnerId,
                'tasks' => '["MANAGE","MESSAGE","DEVELOP","ANALYZE","CARE"]',
                'access_token' => $accessToken,
            ]);
    }

    /**
     * GET /{wabaId}/assigned_users?business={businessId}&access_token={accessToken}
     */
    public static function assignedUsers(string $wabaId, string $businessId, string $accessToken, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/assigned_users", 'GET', $timeout))
            ->withQuery([
                'business' => $businessId,
                'access_token' => $accessToken,
            ]);
    }

    /**
     * POST /{wabaId}/assigned_users?tasks=MANAGE&user={userId}&access_token={accessToken}
     */
    public static function assignUser(string $wabaId, string $userId, string $accessToken, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/assigned_users", 'POST', $timeout))
            ->withQuery([
                'user' => $userId,
                'tasks' => 'MANAGE',
                'access_token' => $accessToken,
            ]);
    }

    /**
     * POST /{wabaId}/subscribed_apps
     */
    public static function subscribeApp(string $wabaId, string $accessToken, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/subscribed_apps", 'POST', $timeout))->withHeaders(['Authorization' => 'Bearer ' . $accessToken]);
    }

    /**
     * DELETE /{wabaId}/subscribed_apps
     */
    public static function unsubscribeApp(string $wabaId, int $timeout = 60): ApiRequest
    {
        return new ApiRequest("{$wabaId}/subscribed_apps", 'DELETE', $timeout);
    }

    /**
     * GET /oauth/access_token?client_id=...&client_secret=...&code=...&redirect_uri=...
     */
    public static function exchangeCode(string $clientId, string $clientSecret, string $code, string $redirectUri, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest('oauth/access_token', 'GET', $timeout))
            ->withQuery([
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]);
    }

    /**
     * GET /{phoneNumber}?access_token={accessToken}
     */
    public static function getPhoneNumber(string $phoneNumberId, string $accessToken, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest($phoneNumberId, 'GET', $timeout))
            ->withQuery([
                'access_token' => $accessToken,
            ]);
    }

    /**
     * GET /{wabaId}/phone_numbers
     */
    public static function getPhoneNumbers(string $wabaId, int $timeout = 60): ApiRequest
    {
        return new ApiRequest("{$wabaId}/phone_numbers", 'GET', $timeout);
    }

    /**
     * GET /{wabaId}
     *
     * @param string[] $fields
     */
    public static function getWaba(string $wabaId, array $fields = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest($wabaId, 'GET', $timeout);
        if ($fields !== []) {
            $request = $request->withQuery(['fields' => implode(',', $fields)]);
        }
        return $request;
    }

    /**
     * GET /{phoneNumberId}/whatsapp_business_profile
     *
     * @param string[] $fields
     */
    public static function getBusinessProfile(string $phoneNumberId, array $fields = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest("{$phoneNumberId}/whatsapp_business_profile", 'GET', $timeout);
        if ($fields !== []) {
            $request = $request->withQuery(['fields' => implode(',', $fields)]);
        }
        return $request;
    }

    /**
     * POST /{phoneNumberId}/whatsapp_business_profile
     *
     * @param array<string,mixed>|JsonSerializable $profile
     */
    public static function updateBusinessProfile(string $phoneNumberId, array|JsonSerializable $profile, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$phoneNumberId}/whatsapp_business_profile", 'POST', $timeout))
            ->withJsonBody($profile);
    }

    /**
     * GET /{wabaId}/conversation_analytics
     *
     * @param array<string,mixed> $params
     */
    public static function getConversationAnalytics(string $wabaId, array $params = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest("{$wabaId}/conversation_analytics", 'GET', $timeout);
        if ($params !== []) {
            $request = $request->withQuery($params);
        }
        return $request;
    }

    /**
     * GET /{wabaId}/message_templates
     *
     * @param array<string,mixed> $params
     */
    public static function getMessageTemplates(string $wabaId, array $params = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest("{$wabaId}/message_templates", 'GET', $timeout);
        if ($params !== []) {
            $request = $request->withQuery($params);
        }
        return $request;
    }

    /**
     * POST /{wabaId}/message_templates
     *
     * @param array<string,mixed>|JsonSerializable $template
     */
    public static function createMessageTemplate(string $wabaId, array|JsonSerializable $template, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/message_templates", 'POST', $timeout))
            ->withJsonBody($template);
    }

    /**
     * DELETE /{wabaId}/message_templates?name={name}
     *
     * @param array<string,mixed> $extraQuery
     */
    public static function deleteMessageTemplate(string $wabaId, string $name, array $extraQuery = [], int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$wabaId}/message_templates", 'DELETE', $timeout))
            ->withQuery(array_merge(['name' => $name], $extraQuery));
    }

    /**
     * GET /{wabaId}/template_analytics
     *
     * @param array<string,mixed> $params
     */
    public static function getTemplateAnalytics(string $wabaId, array $params = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest("{$wabaId}/template_analytics", 'GET', $timeout);
        if ($params !== []) {
            $request = $request->withQuery($params);
        }
        return $request;
    }

    /**
     * POST /{phoneNumberId}/media
     */
    public static function uploadMedia(string $phoneNumberId, string $multipartBody, string $boundary, int $timeout = 60): ApiRequest
    {
        return (new ApiRequest("{$phoneNumberId}/media", 'POST', $timeout))
            ->withHeader('Content-Type', "multipart/form-data; boundary={$boundary}")
            ->withRawBody($multipartBody);
    }

    /**
     * GET /{mediaId}
     */
    public static function getMedia(string $mediaId, int $timeout = 60): ApiRequest
    {
        return new ApiRequest($mediaId, 'GET', $timeout);
    }

    /**
     * DELETE /{mediaId}
     */
    public static function deleteMedia(string $mediaId, int $timeout = 60): ApiRequest
    {
        return new ApiRequest($mediaId, 'DELETE', $timeout);
    }

    /**
     * GET /{phoneNumberId}/groups
     *
     * @param array<string,mixed> $params
     */
    public static function getGroups(string $phoneNumberId, array $params = [], int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest("{$phoneNumberId}/groups", 'GET', $timeout);
        if ($params !== []) {
            $request = $request->withQuery($params);
        }
        return $request;
    }

    /**
     * Build a custom request for newly released endpoints.
     *
     * @param array<string,mixed> $query
     */
    public static function custom(string $path, string $method, array $query = [], array|JsonSerializable|null $body = null, int $timeout = 60): ApiRequest
    {
        $request = new ApiRequest($path, strtoupper($method), $timeout);
        if ($query !== []) {
            $request = $request->withQuery($query);
        }
        if ($body !== null) {
            $request = $request->withJsonBody($body);
        }
        return $request;
    }
}
