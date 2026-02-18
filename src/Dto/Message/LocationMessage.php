<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class LocationMessage extends Message
{
    public function __construct(
        string $to,
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly ?string $name = null,
        private readonly ?string $address = null,
        ?string $reply_to = null
    ) {
        parent::__construct('location', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $location = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        if ($this->name !== null) {
            $location['name'] = $this->name;
        }
        if ($this->address !== null) {
            $location['address'] = $this->address;
        }

        $message['location'] = $location;
        return $message;
    }
}
