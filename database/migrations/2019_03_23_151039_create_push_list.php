<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_list', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('push_schedule_id')->nullable();
            $table->integer('push_line_id');
            $table->enum('status', ['未發送', '處理中','已處理']);
            $table->string('push_msg');
            $table->dateTime('push_at');
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
        Schema::dropIfExists('push_list');
    }
}
