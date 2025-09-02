<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

class GetPhoneNumberApiResponse extends ApiResponse
{
    public function getCodeVerificationStatus(): string
    {
        return $this->httpResponse->getDecodedBody()["code_verification_status"];
    }

    public function getDisplayPhoneNumber(): string
    {
        return $this->httpResponse->getDecodedBody()["display_phone_number"];
    }

    public function getQualityRating(): string
    {
        return $this->httpResponse->getDecodedBody()["quality_rating"];
    }

    public function getVerifiedName(): string
    {
        return $this->httpResponse->getDecodedBody()["verified_name"];
    }
}
