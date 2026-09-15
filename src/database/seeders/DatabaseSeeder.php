<?php

namespace Database\Seeders;

use Database\Seeders\App\Company\CompanyLocationsSeeder;
use Database\Seeders\App\Company\DepartmentSeeder;
use Database\Seeders\App\JobPost\ExperienceLevelSeeder;
use Database\Seeders\App\JobPost\JobTypeSeeder;
use Database\Seeders\App\Notification\NotificationChannelTableSeeder;
use Database\Seeders\App\Notification\NotificationEventTableSeeder;
use Database\Seeders\App\Notification\NotificationSettingsSeeder;
use Database\Seeders\App\Notification\NotificationTemplateSeeder;
use Database\Seeders\App\PermissionAppSeeder;
use Database\Seeders\App\Recruitment\StageSeeder;
use Database\Seeders\App\SettingTableSeeder;
use Database\Seeders\Auth\PermissionRoleTableSeeder;
use Database\Seeders\Auth\TypeSeeder;
use Database\Seeders\Auth\UserRoleTableSeeder;
use Database\Seeders\Auth\UserTableSeeder;
use Database\Seeders\Builder\CustomFieldTypeSeeder;
use Database\Seeders\Status\StatusSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;
    use DisableForeignKeys;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Artisan::call('cache:clear');
        Model::unguard();
        activity()->withoutLogs(function () {
            
            $this->disableForeignKeys();

            $this->call(StatusSeeder::class);
            $this->call(TypeSeeder::class);
            $this->call(UserTableSeeder::class);
            $this->call(PermissionAppSeeder::class);
            $this->call(PermissionRoleTableSeeder::class);
            $this->call(UserRoleTableSeeder::class);
            $this->call(SettingTableSeeder::class);
            $this->call(CustomFieldTypeSeeder::class);
            $this->call(NotificationChannelTableSeeder::class);
            $this->call(NotificationEventTableSeeder::class);
            $this->call(NotificationSettingsSeeder::class);
            $this->call(NotificationTemplateSeeder::class);

            /* For Job point seeder */
            $this->call(JobTypeSeeder::class);
            $this->call(StageSeeder::class);
            $this->call(ExperienceLevelSeeder::class);

            /* For Company seeder */
            $this->call(CompanyLocationsSeeder::class);
            $this->call(DepartmentSeeder::class);

            $this->enableForeignKeys();

        });

        Model::reguard();
    }
}
