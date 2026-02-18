<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Exception\ApiResponseException;

//https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/
class ErrorApiResponse extends ApiResponse
{
    public static function throw(): ApiResponseException
    {
        return new ApiResponseException("");
    }
}
