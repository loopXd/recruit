<?php

namespace Database\Seeders\App\Notification;

use App\Models\Core\Auth\Type;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationEventTableSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $appTypeId = Type::findByAlias('app')->id;
        $events = [
            [
                'name' => 'user_invitation', //keep
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'password_reset', //keep
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'user_joined', //keep
                'type_id' => $appTypeId,
            ],

            //role
            [
                'name' => 'roles_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'roles_updated',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'roles_deleted',
                'type_id' => $appTypeId,
            ],

            //Application
            [
                'name' => 'candidate_disqualified',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'disqualification_mail_for_candidate',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'event_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'create_event_mail_for_candidate',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'note_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'job_applied',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'job_apply_response_for_candidate',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'job_alert',
                'type_id' => $appTypeId,
            ],
        ];
        NotificationEvent::query()->truncate();
        NotificationEvent::query()->insert($events);
        $this->enableForeignKeys();
    }
}
