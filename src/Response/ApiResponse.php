<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

abstract class ApiResponse
{
    public abstract function __construct(HttpResponse $response);
}
