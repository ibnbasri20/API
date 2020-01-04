<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('users_id')->index();
            $table->string('first_name');
            $table->sting('last_name');
            $table->sting('photo')->nullable();
            $table->uuid('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_info');
    }
}
