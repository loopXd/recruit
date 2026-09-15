<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_post_id')->nullable()->constrained();

            $table->mediumText('application_settings');
            $table->mediumText('editor_settings');
            $table->longText('template');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_application_settings');
    }
}
