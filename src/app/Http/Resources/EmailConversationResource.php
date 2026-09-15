<?php

namespace App\Http\Resources;

use App\Repositories\Core\Setting\SettingRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailConversationResource extends JsonResource
{
    public function toArray($request)
    {
        $app_name = resolve(SettingRepository::class)
            ->findAppSettingWithName('company_name','app');

        $app_name = $app_name ? $app_name->value : 'N/A';

        return  [
            'id' => $this->id,
            'sender' => $this->sender,
            'text_html' => $this->text_html,
            'from' => $this->sender === 'user' ? ($this->user ? $this->user->full_name : $app_name) : $this->applicant->full_name,
            'from_email' => $this->sender === 'user' ? ($this->user ? $this->user->email : $app_name) : $this->applicant->email,
            'to' => $this->sender === 'user' ? $this->applicant->full_name : ($this->user ? $this->user->full_name : $app_name),
            'to_email' => $this->sender === 'user' ? $this->applicant->email : ($this->user ? $this->user->email : ''),
            'date' => $this->created_at,
            'attachments' => $this->attachments ?? [],
        ];

    }
}
