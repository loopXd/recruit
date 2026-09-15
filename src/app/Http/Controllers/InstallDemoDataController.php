<?php


namespace App\Http\Controllers;


use App\Helpers\traits\SetIiiTrait;

use Gainhq\Installer\App\Managers\StorageManager;
use Illuminate\Support\Facades\Artisan;

class InstallDemoDataController extends Controller
{
    use SetIiiTrait;

    protected $newData = [];

    public function run(): bool
    {
        try {
            if (env('INSTALL_DEMO_DATA')) {

                $this->setMemoryLimit('500M');
                $this->setExecutionTime(500);

                Artisan::call('clear-compiled');
                Artisan::call('view:clear');

                Artisan::call('config:clear');
                Artisan::call('cache:clear');

                Artisan::call('migrate:fresh --force');
                Artisan::call('db:demo');

                Artisan::call('storage:link');
                Artisan::call('queue:restart');

                resolve(StorageManager::class)->link();

            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function testStorageLink()
    {
        return resolve(StorageManager::class)->handle();
    }

    public function phpInfo()
    {
        return phpinfo();
    }

    public function systemCacheClear()
    {
        $this->setMemoryLimit('500M');
        $this->setExecutionTime(500);

        Artisan::call('clear-compiled');
        Artisan::call('view:clear');

        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        // Artisan::call('storage:link');
        Artisan::call('queue:restart');

        // resolve(StorageManager::class)->link();

        //Generate Sitemap
        Artisan::call('sitemap:generate');

        return true;
    }

    public function installSiteMap()
    {
        Artisan::call('sitemap:generate');
        return 'sitemap updated';
    }

}