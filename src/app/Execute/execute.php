<?php

namespace app\Execute;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class execute
{
    public function executeOperation($path, $version)
    {
        $this->runMigration();
        $this->runSeeder();
        $this->cacheClear();
        //$this->sqlExecution($path, $version);
        $this->updateAppVersion($path, $version);
    }

    public function runMigration()
    {
        try {
            Artisan::call('migrate --force');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function runSeeder()
    {
        try {
            Artisan::call('db:seed --class=AppVersionUpdateSeeder --force');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function cacheClear()
    {
        try {
            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function sqlExecution($path, $version)
    {
        $File = new Filesystem;

        $filePath = public_path($path . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $version . '.sql');

        if ($File->exists($filePath)) {
            $getFile = $File->get($filePath);
            DB::connection()->getPdo()->exec($getFile);
        }
    }

    public function updateAppVersion($path, $version)
    {
        $File = new Filesystem;

        $filePath = public_path($path . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'gain.php');

        if ($File->exists($filePath)) {
            $File->move($filePath, base_path('config' . DIRECTORY_SEPARATOR . 'gain.php'));
        }
    }
}

