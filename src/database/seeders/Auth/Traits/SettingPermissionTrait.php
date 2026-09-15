<?php

namespace Database\Seeders\Auth\Traits;

trait SettingPermissionTrait
{
    public function setting($type, $group = null)
    {
        return [
            //---------------------------------------------------
            [
                'name' => 'can_view_job_setting',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_view_integrations',
                'type_id' => $type,
                'group_name' => $group ?? 'integrations',
            ],

            //===============Apply Form Setting=================
            [
                'name' => 'can_manage_global_application_form',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            //===============EVENT Type=================
            [
                'name' => 'can_view_event_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_event_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_event_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_event_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            //===============Job Type=================
            [
                'name' => 'can_view_job_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_job_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_job_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_job_type',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            //===============Experience Level=================
            [
                'name' => 'can_view_experience_level',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_experience_level',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_experience_level',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_experience_level',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            //===============Company Location=================
            [
                'name' => 'can_view_company_location',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_company_location',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_company_location',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_company_location',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            //===============Department=================
            [
                'name' => 'can_view_department',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_department',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_department',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_department',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],

            //==============Stage====================
            [
                'name' => 'can_view_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_create_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_update_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            [
                'name' => 'can_delete_stage',
                'type_id' => $type,
                'group_name' => $group ?? 'job_settings',
            ],
            // ----------------------------------------
            [
                'name' => 'view_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'update_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'view_delivery_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'update_delivery_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'view_notification_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'update_notification_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'view_cron_job_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'view_imap_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
            [
                'name' => 'view_update_settings',
                'type_id' => $type,
                'group_name' => $group ?? 'app_settings'
            ],
        ];
    }
}
