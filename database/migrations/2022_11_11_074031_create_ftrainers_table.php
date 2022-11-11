<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFtrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftrainers', function (Blueprint $table) {
            $table->id();
            $table->longText("photograph")->nullable();
            $table->string("nf_x0020_license")->nullable();
            $table->string("first_x0020_name")->nullable();
            $table->string("family_x0020_name")->nullable();
            $table->string("gender")->nullable();
            $table->string("nationality")->nullable();
            $table->string("nationality_short")->nullable();
            $table->string("dob")->nullable();
            $table->string("stable")->nullable();
            $table->string("fei_x0020_reg")->nullable();
            $table->string("telephone")->nullable();
            $table->string("mobile")->nullable();
            $table->string("email")->nullable();
            $table->string("division")->nullable();
            $table->string("registered_x0020_season")->nullable();
            $table->string("active")->nullable();
            $table->string("trainerid")->nullable();
            $table->string("stableid")->nullable();
            $table->string("divisionid")->nullable();
            $table->string("adminuser")->nullable();
            $table->string("nationalityid")->nullable();
            $table->string("address")->nullable();
            $table->string("pobox")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("country_short")->nullable();
            $table->string("homeaddress")->nullable();
            $table->string("homecity")->nullable();
            $table->string("homecountry")->nullable();
            $table->string("homecountry_short")->nullable();
            $table->string("weight")->nullable();
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
        Schema::dropIfExists('ftrainers');
    }
}
