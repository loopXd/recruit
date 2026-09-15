<?php

namespace Database\Seeders\Production;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class ProductionPermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Role::query()->truncate();

        $superAdmin = User::query()->first();

        $roles = [
            [
                'name' => config('access.users.app_admin_role'),
                'is_admin' => 1,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 1
            ],
            [
                'name' => config('access.users.app_candidate_role'),
                'is_admin' => 0,
                'type_id' => Type::findByAlias('app')->id,
                'created_by' => $superAdmin->id,
                'is_default' => 0
            ],
        ];

        Role::query()->insert($roles);

        $permissions = Permission::all();

        //Candidate
        $candidate = Role::query()->where('name', 'Candidate')->first();
        $candidatesRoles = [
            'can_view_my_story',
            'can_view_job_post','can_view_job_applicant','manage_dashboard',
            'can_manage_get_job_alert','can_manage_set_job_alert',
            'can_last_applied_job_applicant',
            'view_my_story'
        ];

        foreach($permissions as $permission) {
            if(in_array($permission->name, $candidatesRoles)) {
                $candidate->permissions()->attach(["permission_id" => $permission->id]);
            }
        }

        $this->enableForeignKeys();
    }

}
