<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_days', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        Schema::create('day_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->unsignedInteger('day_id');
            $table->string('status', 50);
            $table->string('type', 50);
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
        Schema::dropIfExists('user_days');
        Schema::dropIfExists('day_tasks');
    }
}
