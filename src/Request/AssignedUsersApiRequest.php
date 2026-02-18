<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

class AssignedUsersApiRequest extends ApiRequest
{
    public function __construct(string $business_id, string $access_token, string $waba_id, int $timeout = 60)
    {
        // For compatibility: keep full path+query assembly here
        $uri = "{$waba_id}/assigned_users";
        $query = http_build_query([
            'business' => $business_id,
            'access_token' => $access_token,
        ]);
        $uri .= '?' . $query;

        parent::__construct($uri, 'GET', $timeout);
    }
}
