<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailInboxIdsTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create('email_inbox_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('last_read_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->drop('email_inbox_ids');
    }
};
