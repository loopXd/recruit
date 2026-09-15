<?php

namespace Database\Seeders\App\Applicant;

use App\Models\App\Applicant\Attendee;
use App\Models\App\Applicant\Event;
use App\Models\App\Recruitment\HiringTeam;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Attendee::query()->truncate();

        $events = Event::query()->get();
        $recruiterIds = HiringTeam::query()->get()->pluck('id');

        $attendees = [];

        foreach ($events as $event) {
            foreach ($recruiterIds as $recruiterId) {
                array_push($attendees, [
                    "event_id" => $event->id,
                    "hiring_team_id" => $recruiterId
                ]);
            }
        }

        Attendee::query()->insert($attendees);
        $this->enableForeignKeys();
    }

}
