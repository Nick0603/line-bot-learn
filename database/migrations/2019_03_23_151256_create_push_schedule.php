<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->time('generator_time');
            $table->dateTime('last_generator_at');
            $table->enum('status', ['未產生', '處理中','已產生']);
            $table->enum('active_status',['關閉中','啟動中']);
            $table->string('push_msg');
            $table->time('push_time_at');
            $table->integer('push_line_id');
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
        Schema::dropIfExists('push_schedule');
    }
}
