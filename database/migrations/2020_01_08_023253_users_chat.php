<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chat', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
            $table->uuid('chat_id')->index();
            $table->string('message');
            $table->timestamps();
            $table->foreign('chat_id')->references('id')->on('chat');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chat');
    }
}
