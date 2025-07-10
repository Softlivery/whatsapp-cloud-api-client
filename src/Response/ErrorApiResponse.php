<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;
use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

//https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/
class ErrorApiResponse extends ApiResponse
{

    public function __construct(HttpResponse $response)
    {
    }

    public static function throw(): ApiResponseException
    {
        return new ApiResponseException("");
    }
}
