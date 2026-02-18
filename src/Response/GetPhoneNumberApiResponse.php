<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

class GetPhoneNumberApiResponse extends ApiResponse
{
    public function getCodeVerificationStatus(): ?string
    {
        return $this->getString('code_verification_status');
    }

    public function getDisplayPhoneNumber(): ?string
    {
        return $this->getString('display_phone_number');
    }

    public function getQualityRating(): ?string
    {
        return $this->getString('quality_rating');
    }

    public function getVerifiedName(): ?string
    {
        return $this->getString('verified_name');
    }
}
