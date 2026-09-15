<?php

namespace Database\Seeders\Status;

use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
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
        Status::query()->truncate();
        $statuses = [
            [
                'name' => 'status_active',
                'type' => 'user',
                'class' => 'success'
            ],
            [
                'name' => 'status_inactive',
                'type' => 'user',
                'class' => 'danger'
            ],
            [
                'name' => 'status_invited',
                'type' => 'user',
                'class' => 'purple'
            ],
            [
                'name' => 'status_new',
                'type' => 'job_applicant',
                'class' => 'primary'
            ],
            [
                'name' => 'status_in_progress',
                'type' => 'job_applicant',
                'class' => 'purple'
            ],
            [
                'name' => 'status_hired',
                'type' => 'job_applicant',
                'class' => 'success'
            ],
            [
                'name' => 'status_disqualified',
                'type' => 'job_applicant',
                'class' => 'danger'
            ],
            [
                'name' => 'status_draft',
                'type' => 'job_post',
                'class' => 'warning'
            ],
            [
                'name' => 'status_open',
                'type' => 'job_post',
                'class' => 'success'
            ],
            [
                'name' => 'status_closed',
                'type' => 'job_post',
                'class' => 'danger'
            ],
            [
                'name' => 'status_pending',
                'type' => 'todo',
                'class' => 'primary'
            ],
            [
                'name' => 'status_done',
                'type' => 'todo',
                'class' => 'success'
            ],
        ];

        Status::query()->insert($statuses);

        $this->enableForeignKeys();
    }
}
