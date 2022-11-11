<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFhorsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fhorses', function (Blueprint $table) {
            $table->id();
            $table->string("nfpassportnumber")->nullable();
            $table->string("active")->nullable();
            $table->string("horseid")->nullable();
            $table->string("nfregistration")->nullable();
            $table->string("name")->nullable();
            $table->string("breed")->nullable();
            $table->string("countryorigin")->nullable();
            $table->string("countryoriginshort")->nullable();
            $table->string("dob")->nullable();
            $table->string("gender")->nullable();
            $table->string("color")->nullable();
            $table->string("trainer")->nullable();
            $table->string("owner")->nullable();
            $table->string("stable")->nullable();
            $table->string("feipassport")->nullable();
            $table->string("microchip")->nullable();
            $table->string("division")->nullable();
            $table->string("stableid")->nullable();
            $table->string("divisionid")->nullable();
            $table->string("adminuser")->nullable();
            $table->string("breedid")->nullable();
            $table->string("colourid")->nullable();
            $table->string("genderid")->nullable();
            $table->string("countryoforiginid")->nullable();
            $table->string("trainerid")->nullable();
            $table->string("ownerid")->nullable();
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
        Schema::dropIfExists('fhorses');
    }
}
