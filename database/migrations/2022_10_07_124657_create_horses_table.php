<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horses', function (Blueprint $table) {
            $table->id('horse_id');
            $table->string('name')->index();
            $table->string('originalName')->index();
            $table->string('countryOfBirth')->index();
            $table->enum('breed', ['A', 'P', 'C'])->index();
            $table->string('breeder')->nullable()->index();
            $table->date('birthday');
            $table->enum('gender', ['M', 'F'])->index();
            $table->string('colour')->nullable();
            $table->string('microchipNo')->index();
            $table->string('uelnNo')->index();
            $table->string('countryOfResidence');
            $table->string('sire');
            $table->string('dam');
            $table->string('sireOfDam');
            $table->string('feiPassportNo')->index();
            $table->date('feiExpireDate');
            $table->string('feiRegNo')->index();
            $table->integer('owner_id')->index();
            $table->integer('trainer_id')->index();
            $table->enum('status', ['P', 'A', 'R'])->default('P')->index();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('horses');
    }
}
