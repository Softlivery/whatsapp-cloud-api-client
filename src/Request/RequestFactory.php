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
        return (new ApiRequest("{$fromPhoneNumberId}/messages", 'POST', $timeout))
            ->withJsonBody($message);
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
     * GET oauth/access_token?client_id=...&client_secret=...&code=...&redirect_uri=...
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
}
