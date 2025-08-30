<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

class CodeExchangeApiRequest extends ApiRequest
{
    public function __construct(string $client_id, string $client_secret, string $code, string $redirect_url)
    {
        $uri = 'oauth/access_token';

        $query = http_build_query([
            'client_id' => $client_id,
            //'redirect_uri' => $redirect_uri,
            'client_secret' => $client_secret,
            'code' => $code
        ]);
        $uri .= '?' . $query;

        parent::__construct($uri, 'GET');
    }
}
