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
        Schema::create('events', function (Blueprint $table)
        {
            $table->bigInteger('id')->primary()->unsigned();
            $table->string('name');
            $table->integer('category')->nullable();
            $table->integer('sub_category')->nullable();
            $table->uuid('publisher')->index();
            $table->string('photo')->nullable();
            $table->date('start');
            $table->date('end');
            $table->double('lat');
            $table->double('long');
            $table->longText('location');
            $table->unsignedBigInteger('group_id')->index();
            $table->timestamps();
            $table->foreign('publisher')->references('id')->on('users');
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
