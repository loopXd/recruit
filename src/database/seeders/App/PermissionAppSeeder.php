<?php

namespace Database\Seeders\App;

use App\Models\Core\Auth\Type;
use Illuminate\Database\Seeder;
use App\Models\Core\Auth\Permission;
use Database\Seeders\App\Traits\ApplicantPermissionTrait;
use Database\Seeders\App\Traits\CareerPermissionTrait;
use Database\Seeders\App\Traits\JobPostPermissionTrait;
use Database\Seeders\Auth\Traits\RolePermissionTrait;
use Database\Seeders\Auth\Traits\SettingPermissionTrait;
use Database\Seeders\Auth\Traits\TemplatePermissionTrait;
use Database\Seeders\Auth\Traits\UserPermissionTrait;

use Database\Seeders\Traits\DisableForeignKeys;

class PermissionAppSeeder extends Seeder
{
    use DisableForeignKeys;
    use ApplicantPermissionTrait;
    use JobPostPermissionTrait;
    use CareerPermissionTrait;
    use UserPermissionTrait;
    use RolePermissionTrait;
    use SettingPermissionTrait;
    use TemplatePermissionTrait;

    public function run()
    {
        $this->disableForeignKeys();
        Permission::query()->truncate();
        $appId = Type::findByAlias('app')->id;

        $core_permissions = array_merge(
            $this->setting($appId),
            $this->user($appId),
            $this->role($appId),
            $this->template($appId)
        );

        $permissions = array_merge(
            $this->jobPost($appId),
            $this->applicant($appId),
            $this->career($appId),
            $core_permissions
        );
        $this->enableForeignKeys();
        Permission::query()->insert($permissions);
    }
}
