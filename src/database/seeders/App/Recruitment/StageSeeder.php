<?php

namespace Database\Seeders\App\Recruitment;

use App\Models\App\Recruitment\Stage;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
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

        Stage::query()->truncate();

        $stages = [
            [
                'name' => 'new',
            ],
            [
                'name' => 'entrevista',
            ],
            [
                'name' => 'pré-seleção',
            ],
            [
                'name' => 'hired',
            ],
            [
                'name' => 'disqualified',
            ],
        ];

        Stage::query()->insert($stages);
        $this->enableForeignKeys();
    }
}
