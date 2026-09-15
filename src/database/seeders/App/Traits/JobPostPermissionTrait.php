<?php

namespace Database\Seeders\App\Traits;

trait JobPostPermissionTrait
{
    public function jobPost($type, $group = null)
    {
        return [

            [
                'name' => 'can_view_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_create_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_update_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_delete_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            // ================Custom Rules of Job Post======================

            [
                'name' => 'can_view_job_overview',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_view_applicants_job_overview',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_sharable_link_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_view_job_post_setting',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_update_job_post_setting_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            /*
             * Done by can_change_job_post_status
             * [
                'name' => 'can_publish_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_view_apply_form_setting',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_apply_form_setting',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],*/
            [
                'name' => 'can_manage_job_post_application_form',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_change_job_post_status',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],

            //==============Job Stage====================
            [
                'name' => 'can_view_job_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_create_job_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_update_job_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_delete_job_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],

            //==============Hiring Team====================
            /*[
                'name' => 'can_get_teams_by_job_post',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],*/
            [
                'name' => 'can_view_hiring_team',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_create_hiring_team',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            /*[
                'name' => 'can_update_hiring_team',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],*/
            [
                'name' => 'can_delete_hiring_team',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            //Social Sharable
            [
                'name' => 'can_manage_sharable_thumbnail',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_manage_get_job_alert',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_manage_set_job_alert',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'can_last_applied_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
                        [
                'name' => 'view_my_story',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ],
            [
                'name' => 'manage_dashboard',
                'type_id' => $type,
                'group_name' => $group ?? 'job_post',
            ]
        ];
    }
}
