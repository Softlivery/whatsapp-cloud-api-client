<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Response;

use Softlivery\WhatsappCloudApiClient\Http\HttpResponse;

class AssignedUsersApiResponse extends ApiResponse
{
    public function __construct(HttpResponse $response)
    {
        parent::__construct($response);
    }

    /**
     * Returns the assigned users list from the "data" field,
     * or an empty array if not present.
     *
     * @return array<int, array<string,mixed>>
     */
    public function getAssignedUsers(): array
    {
        return $this->getArray('data', []);
    }
}
