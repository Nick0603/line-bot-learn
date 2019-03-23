<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('push_list_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->enum('status', ['未發送', '處理中','已處理']);
            $table->string('push_msg');
            $table->string('push_token');
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
        Schema::dropIfExists('push_tmp');
    }
}
