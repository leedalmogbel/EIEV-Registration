<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id('entry_id');
            $table->integer('race_id')->index();
            $table->integer('rider_id')->index();
            $table->integer('horse_id')->index();
            $table->integer('user_id')->index();
            $table->integer('sequence')->default(0)->index();
            $table->string('number')->default('0')->index();
            $table->enum('status', ['P', 'A', 'R', 'F'])->defaul('P')->index();
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
        Schema::dropIfExists('entries');
    }
}
