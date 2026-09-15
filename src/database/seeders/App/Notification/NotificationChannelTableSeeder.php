<?php

namespace Database\Seeders\App\Notification;

use App\Models\Core\Setting\NotificationChannel;
use Illuminate\Database\Seeder;

class NotificationChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $channels = [
            ['name' => 'database'],
            ['name' => 'mail'],
        ];

        NotificationChannel::query()->insert($channels);
    }
}
