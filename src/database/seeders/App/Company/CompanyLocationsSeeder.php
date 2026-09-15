<?php

namespace Database\Seeders\App\Company;

use App\Models\App\Company\CompanyLocation;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class CompanyLocationsSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        CompanyLocation::query()->truncate();
        $departaments = [
            ['address' => 'Serra']
        ];

        CompanyLocation::query()->insert($departaments);
        $this->enableForeignKeys();
    }
}
