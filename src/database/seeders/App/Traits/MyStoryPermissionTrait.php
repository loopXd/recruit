<?php

namespace Database\Seeders\App\Traits;

trait MyStoryPermissionTrait
{
    public function myStory($type, $group = null)
    {
        return [

            //===============APPLICANT============================
            [
                'name' => 'can_view_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'my_story',
            ],
        ];
    }
}
