<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Http;

use Softlivery\WhatsappCloudApiClient\Request\ApiRequest;

interface HttpClient
{

    public function send(ApiRequest $request): HttpResponse;
}
