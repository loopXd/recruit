<?php

namespace Database\Seeders\App\Traits;

trait ApplicantPermissionTrait
{
    public function applicant($type, $group = null)
    {
        return [

            //===============APPLICANT============================
            [
                'name' => 'can_view_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_create_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_update_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_view_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_delete_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],

            //==============JOB APPLICANT============================

            [
                'name' => 'can_create_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_delete_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_view_job_applicant_timeline',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_view_applicant_details',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_change_stage_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_change_review_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_send_email_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_disqualify_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_view_note_by_recruiters',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_export_applicants',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],

            //=========EVENT - Job Applicant===========
            [
                'name' => 'can_view_events_for_job_applicant',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],

            //===============EVENT=================
            [
                'name' => 'can_view_event',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_create_event',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_update_event',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_delete_event',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            //===============NOTE=================
            [
                'name' => 'can_view_note',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_create_note',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_update_note',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],
            [
                'name' => 'can_delete_note',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],

            //===============Attendee=================
            /*[
                'name' => 'can_manage_attendee',
                'type_id' => $type,
                'group_name' => $group ?? 'candidates',
            ],*/

        ];
    }
}
