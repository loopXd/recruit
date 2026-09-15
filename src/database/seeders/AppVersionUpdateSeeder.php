<?php

namespace Database\Seeders;

use App\Models\App\JobPost\ExperienceLevel;
use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use App\Models\Core\Setting\Setting;
use Database\Seeders\App\JobPost\ExperienceLevelSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AppVersionUpdateSeeder extends Seeder
{
    use TruncateTable;
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $experienceLevel =  ExperienceLevel::query()->first();
        if (!$experienceLevel) {
            $this->call(ExperienceLevelSeeder::class);
        }

        $this->insertStorageSettings()
            ->insertCandidateRole();
        try {
            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function insertStorageSettings(): static
    {
        $storageSetting =  Setting::query()->where('context', 'storage_configuration')->first();
        if (!$storageSetting) {
            Setting::query()->insert(
                [
                    'name' => 'storage_type',
                    'value' => encrypt('public'),
                    'settingable_type' => null,
                    'settingable_id' => null,
                    'context' => 'storage_configuration',
                    'autoload' => 0,
                    'public' => 1,
                ]
            );
        }

        return $this;
    }

    public function insertCandidateRole(): static
    {
        // Check if 'Candidate' role already exists
        $candidate = Role::query()->where('name', 'Candidate')->first();

        if (!$candidate) {
            // If 'Candidate' role doesn't exist, create it
            $superAdmin = User::query()->first();
            $roles = [
                [
                    'name' => config('access.users.app_candidate_role'),
                    'is_admin' => 0,
                    'type_id' => Type::findByAlias('app')->id,
                    'created_by' => $superAdmin->id,
                    'is_default' => 0
                ],
            ];

            Role::query()->insert($roles);

            // Retrieve the newly created 'Candidate' role
            $candidate = Role::query()->where('name', 'Candidate')->first();
        }

        $permissionsToInsert = [
            ['name' => 'can_last_applied_job_applicant', 'group_name' => 'job_post'],
            ['name' => 'view_my_story', 'group_name' => 'job_post'],
            ['name' => 'manage_dashboard', 'group_name' => 'job_post'],
            ['name' => 'can_manage_get_job_alert', 'group_name' => 'job_post'],
            ['name' => 'can_manage_set_job_alert', 'group_name' => 'job_post'],
            ['name' => 'can_view_job_post', 'group_name' => 'job_post'],
            ['name' => 'can_view_job_applicant', 'group_name' => 'candidates'],
        ];

        foreach ($permissionsToInsert as $permissionData) {
            $permission = Permission::query()
                ->where('type_id', 1)
                ->where('name', $permissionData['name'])
                ->where('group_name', $permissionData['group_name'])
                ->first();

            if (!$permission) {
                $permission = Permission::query()->create([
                    'type_id' => 1,
                    'name' => $permissionData['name'],
                    'group_name' => $permissionData['group_name'],
                ]);
            }

            $candidate->permissions()->syncWithoutDetaching(["permission_id" => $permission->id]);
        }

        return $this;
    }
}
