<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Add a new string column
            $table->string('new_gender', 255)->nullable();
        });

        // Migrate data from the old enum column to the new string column
        DB::table('applicants')->update([
            'new_gender' => DB::raw('CAST(gender AS CHAR(255))'),
        ]);

        Schema::table('applicants', function (Blueprint $table) {
            // Remove the old enum column
            $table->dropColumn('gender');

            // Rename the new string column to 'gender'
            $table->renameColumn('new_gender', 'gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {

        });
    }
};
