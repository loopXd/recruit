<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationEmailsTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create('application_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained();
            $table->foreignId('job_post_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('sender');
            $table->string('message_id', 128);
            $table->string('reference_id', 128)->nullable();
            $table->text('text_html');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->drop('application_emails');
    }
};
