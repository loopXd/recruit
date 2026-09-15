<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_detail_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('brief');
            $table->longText('content');
            $table->mediumText('page_blocks');
            $table->mediumText('page_style');
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
        Schema::dropIfExists('career_pages');
    }
}
