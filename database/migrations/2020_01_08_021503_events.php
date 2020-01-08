<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table)
        {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('category');
            $table->integer('category');
            $table->integer('publisher');
            $table->string('photo');
            $table->date('start');
            $table->date('end');
            $table->integer('join_id')->unsigned();
            $table->integer('views');
            $table->timestamps();
            $table->foreign('join_id')->references('id')->on('join_event');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
}
