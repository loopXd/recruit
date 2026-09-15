<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        User::query()->truncate();

        // Add the master administrator, user id of 1
        User::query()->create([
            'first_name' => 'Célio',
            'last_name' => 'Junior',
            'email' => 'celio.junior@gruposei.com.br',
            'password' => Hash::make('@Cj3762-*-3463'),
            'status_id' => Status::findByNameAndType('status_active', 'user')->id
        ]);

        $this->enableForeignKeys();
    }
}
