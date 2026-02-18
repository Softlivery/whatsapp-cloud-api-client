<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

class SubscribeAppApiResponse extends ApiResponse
{
    /**
     * Returns the assign user result from the "success" field,
     * or false if not present.
     *
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->getBool('success', false);
    }
}
