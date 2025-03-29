<?php declare(strict_types=1);

namespace Softlivery\WhatsappCloudApiClient\Dto\Webhook;

class EventEntryChangeValueMessageInteractive
{
    public string $type;
    public EventEntryChangeValueMessageInteractiveButtonReply $button_reply;
    public EventEntryChangeValueMessageInteractiveListReply $list_reply;
}
