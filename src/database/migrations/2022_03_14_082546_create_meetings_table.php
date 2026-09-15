<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->longText('meeting_id')->nullable();
            $table->text('user_id')->nullable();
            $table->text('uuid')->nullable();
            $table->text('host_id')->nullable();
            $table->text('host_email')->nullable();
            $table->string('topic')->nullable();
            $table->longText('duration')->comment('minutes')->nullable();
            $table->string('password')->comment('meeting password')->nullable();
            $table->text('start_url')->nullable();
            $table->text('join_url')->nullable();
            $table->text('meeting_channel')->nullable();
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
        Schema::dropIfExists('meetings');
    }
}
