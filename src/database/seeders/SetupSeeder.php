<?php

namespace Database\Seeders;

use Database\Seeders\App\Applicant\EventTypeSeeder;
use Database\Seeders\App\JobPost\ExperienceLevelSeeder;
use Database\Seeders\App\JobPost\JobTypeSeeder;
use Database\Seeders\App\Notification\NotificationChannelTableSeeder;
use Database\Seeders\App\Notification\NotificationEventTableSeeder;
use Database\Seeders\App\Notification\NotificationSettingsSeeder;
use Database\Seeders\App\Notification\NotificationTemplateSeeder;
use Database\Seeders\App\PermissionAppSeeder;
use Database\Seeders\App\Recruitment\StageSeeder;
use Database\Seeders\App\SettingTableSeeder;
use Database\Seeders\Auth\TypeSeeder;
use Database\Seeders\Builder\CustomFieldTypeSeeder;
use Database\Seeders\Production\ProductionPermissionRoleTableSeeder;
use Database\Seeders\Production\ProductionUserRoleTableSeeder;
use Database\Seeders\Status\StatusSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
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
        Model::unguard();
        $this->disableForeignKeys();

        /* For Basic Application Seeder */
        $this->call(StatusSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(PermissionAppSeeder::class);
        $this->call(ProductionPermissionRoleTableSeeder::class);
        $this->call(ProductionUserRoleTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(CustomFieldTypeSeeder::class);
        $this->call(NotificationChannelTableSeeder::class);
        $this->call(NotificationEventTableSeeder::class);
        $this->call(NotificationSettingsSeeder::class);
        $this->call(NotificationTemplateSeeder::class);

        /* For Job point seeder */
        $this->call(JobTypeSeeder::class);
        $this->call(StageSeeder::class);
        $this->call(EventTypeSeeder::class);
        $this->call(ExperienceLevelSeeder::class);


        $this->enableForeignKeys();
        Model::reguard();
    }
}
