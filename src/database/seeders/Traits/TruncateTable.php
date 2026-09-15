<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Class TruncateTable.
 */
trait TruncateTable
{
    /**
     * @param $table
     *
     * @return bool | mixed
     */
    protected function truncate($table)
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::table($table)->truncate();
                return true;

            case 'pgsql':
                DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE');
                return true;

            case 'sqlite':
            case 'sqlsrv':
                DB::statement('DELETE FROM ' . $table);
                return true;
        }

        return false;
    }

    /**
     * @param array $tables
     */
    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
