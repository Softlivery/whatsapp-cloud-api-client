<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Message;

final class TemplateMessage extends Message
{
    /** @param array<int,array<string,mixed>> $components */
    public function __construct(
        string $to,
        private readonly string $name,
        private readonly string $languageCode,
        private readonly array $components = [],
        ?string $reply_to = null
    ) {
        parent::__construct('template', $to, $reply_to);
    }

    public function toArray(): array
    {
        $message = parent::toArray();
        $template = [
            'name' => $this->name,
            'language' => [
                'code' => $this->languageCode,
            ],
        ];

        if ($this->components !== []) {
            $template['components'] = $this->components;
        }

        $message['template'] = $template;
        return $message;
    }
}
