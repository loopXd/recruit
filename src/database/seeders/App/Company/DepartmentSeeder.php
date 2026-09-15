<?php

namespace Database\Seeders\App\Company;

use App\Models\App\Company\Department;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
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
        Department::query()->truncate();
        $departaments = [
            ['name' => 'Comercial'],
            ['name' => 'RH'],
            ['name' => 'TI'],
            ['name' => 'Operacional']
        ];

        Department::query()->insert($departaments);
        $this->enableForeignKeys();
    }
}
