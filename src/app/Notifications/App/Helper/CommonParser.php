<?php

namespace App\Notifications\App\Helper;

trait CommonParser
{
    public function common()
    {
        $this->mailView = 'notification.template';

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{name}' => $this->model->name
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{name}' => $this->model->name
        ]);

        $this->nexmoNotificationContent = optional($this->template()->sms())->parse([
            '{name}' => $this->model->name
        ]);
    }
}