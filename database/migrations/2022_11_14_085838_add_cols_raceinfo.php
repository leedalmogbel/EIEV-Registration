<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsRaceinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fevents', function (Blueprint $table) {
            //
            $table->boolean('isfei')->nullable();
            $table->boolean('maresonly')->nullable();
            $table->boolean('ladiesonly')->nullable();
            $table->boolean('isopencat')->nullable();
            $table->boolean('royalonly')->nullable();
            $table->boolean('pvtonky')->nullable();
            $table->boolean('staggered')->nullable();
            $table->boolean('withsaddle')->nullable();
            $table->string('category')->nullable();
            $table->string('preride')->nullable();
            $table->string('starttime')->nullable();
            $table->string('distance')->nullable();
            $table->longText('nationalities')->nullable();
            $table->string('minriderage')->nullable();
            $table->string('maxriderage')->nullable();
            $table->string('riderweight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fevents', function (Blueprint $table) {
            //
        });
    }
}
