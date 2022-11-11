<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFridersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friders', function (Blueprint $table) {
            $table->id();
            $table->string("nfx0020license")->nullable();
            $table->string("adminuser")->nullable();
            $table->string("firstx0020name")->nullable();
            $table->string("familyx0020name")->nullable();
            $table->string("gender")->nullable();
            $table->string("nationality")->nullable();
            $table->string("nationalityshort")->nullable();
            $table->string("dob")->nullable();
            $table->string("stable")->nullable();
            $table->string("feix0020reg")->nullable();
            $table->string("telephone")->nullable();
            $table->string("mobile")->nullable();
            $table->string("email")->nullable();
            $table->string("division")->nullable();
            $table->string("registeredseasoncode")->nullable();
            $table->string("registeredx0020season")->nullable();
            $table->string("active")->nullable();
            $table->string("riderid")->nullable();
            $table->string("stableid")->nullable();
            $table->string("divisionid")->nullable();
            $table->string("nationalityid")->nullable();
            $table->string("address")->nullable();
            $table->string("pobox")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("countryshort")->nullable();
            $table->string("homeaddress")->nullable();
            $table->string("homecity")->nullable();
            $table->string("homecountry")->nullable();
            $table->string("homecountryshort")->nullable();
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
        Schema::dropIfExists('friders');
    }
}
