<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id('race_id');
            $table->string('title')->index();
            $table->json('contact')->nullable();
            $table->integer('entryCount')->index();
            $table->date('date')->index();
            $table->date('opening')->index();
            $table->date('closing')->index();
            $table->json('pledge')->nullable();
            $table->integer('sheikhStable')->index();
            $table->integer('privateStable')->index();
            $table->text('description')->nullable();
            $table->integer('event_id')->index();
            $table->enum('status', ['P', 'A', 'R'])->defaul('P')->index();
            $table->timestamps();
        });
        
        Schema::create('race_stables', function (Blueprint $table) {
            $table->integer('race_id')->index();
            $table->integer('stable_id')->index();
            $table->integer('entryCount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('races');
        Schema::dropIfExists('race_stables');
    }
}
