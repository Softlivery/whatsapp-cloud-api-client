<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Request;

abstract class AuthorizedApiRequest extends ApiRequest
{
    public function __construct(string $access_token, string $path, string $method, int $timeout = 60)
    {
        parent::__construct($path, $method, $timeout);
        $this->headers['Authorization'] = "Bearer $access_token";
    }
}
