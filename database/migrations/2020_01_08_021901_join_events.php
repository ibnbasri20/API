<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JoinEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_join', function (Blueprint $table)
        {
            $table->bigInteger('id')->primary();
            $table->unsignedBigInteger('id_event')->index();
            $table->uuid('id_users')->index();
            $table->foreign('id_event')->references('id')->on('events');
            $table->foreign('id_users')->references('id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_join');
    }
}
