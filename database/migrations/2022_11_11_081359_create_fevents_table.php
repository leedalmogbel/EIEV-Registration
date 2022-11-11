<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fevents', function (Blueprint $table) {
            $table->id();
            $table->string("statusid")->nullable();
            $table->string("statusname")->nullable();
            $table->string("typeid")->nullable();
            $table->string("typename")->nullable();
            $table->string("divisionid")->nullable();
            $table->string("divisionname")->nullable();
            $table->string("racecity")->nullable();
            $table->string("racecountry")->nullable();
            $table->string("seasonid")->nullable();
            $table->string("seasonname")->nullable();
            $table->string("raceid")->nullable();
            $table->string("racename")->nullable();
            $table->string("racelocation")->nullable();
            $table->string("raceclub")->nullable();
            $table->string("racefromdate")->nullable();
            $table->string("racetodate")->nullable();
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
        Schema::dropIfExists('fevents');
    }
}
