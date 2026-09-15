<?php

namespace Database\Seeders\App\Traits;

trait CareerPermissionTrait
{
    public function career($type, $group = null)
    {
        return [

            //===============Career============================

            [
                'name' => 'can_view_career_page',
                'type_id' => $type,
                'group_name' => $group ?? 'career_page',
            ],
            [
                'name' => 'can_update_career_page',
                'type_id' => $type,
                'group_name' => $group ?? 'career_page',
            ],
        ];
    }
}
