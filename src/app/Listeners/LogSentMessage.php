<?php

namespace App\Listeners;

use App\Helpers\Core\Traits\FileHandler;
use App\Models\App\JobPost\ApplicationEmail;
use Illuminate\Mail\Events\MessageSent;

class LogSentMessage
{
    use FileHandler;
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        if (isset($event->data['event_data']) && count($event->data['event_data']) > 0) {

            $dataSet = [
                'message_id' => $event->sent->getMessageId(),
                'reference_id' =>$event->data['event_data']['reference_id'],
                'sender' => 'user',
                'text_html' => $event->sent->getOriginalMessage()->getHtmlBody(),
            ];

            $applicationEmail = ApplicationEmail::query()->create(array_merge($dataSet, $event->data['event_data']));

            $paths = $event->data['event_data']['attachments'] ?? [];
            foreach ($paths as $path){
                $applicationEmail->attachments()->create([
                    'type' => 'job_post_conversation',
                    'path' => $path
                ]);

            }
        }
    }
}
