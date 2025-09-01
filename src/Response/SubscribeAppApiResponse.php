<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

class SubscribeAppApiResponse extends ApiResponse
{
    public function __construct(HttpResponse $response)
    {
        parent::__construct($response);
    }

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
