<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLentriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lentries', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("eventcode")->nullable();
            $table->string("userid")->nullable();
            $table->string("status")->nullable();
            $table->string("review")->nullable();
            $table->string("startno")->nullable();
            $table->string("horseid")->nullable();
            $table->string("horsename")->nullable();
            $table->string("horsenfid")->nullable();
            $table->string("horsefeiid")->nullable();
            $table->string("riderid")->nullable();
            $table->string("ridername")->nullable();
            $table->string("ridernfid")->nullable();
            $table->string("riderfeiid")->nullable();
            $table->string("riderstableid")->nullable();
            $table->string("stableid")->nullable();
            $table->string("stablename")->nullable();
            $table->string("trainerid")->nullable();
            $table->string("trainername")->nullable();
            $table->string("trainernfid")->nullable();
            $table->string("trainerfeiid")->nullable();
            $table->string("ownerid")->nullable();
            $table->string("ownername")->nullable();
            $table->string("classcode")->nullable();
            $table->string("isfetched")->nullable();
            $table->string("datesubmit")->nullable();
            $table->string("islate")->nullable();
            $table->string("ispreridelate")->nullable();
            $table->string("feiremarks")->nullable();
            $table->string("isfeivalid")->nullable();
            $table->string("seasoncode")->nullable();
            $table->string("fee")->nullable();
            $table->string("feestatus")->nullable();
            $table->string("reference")->nullable();
            $table->string("acceptterms")->nullable();
            $table->string("paytype")->nullable();
            $table->string("posted")->nullable();
            $table->string("chargeable")->nullable();
            $table->string("parentid")->nullable();
            $table->string("withdrawdate")->nullable();
            $table->string("substidate")->nullable();
            $table->string("docsuploaded")->nullable();
            $table->string("remarks")->nullable();
            $table->string("qrval")->nullable();
            $table->string("racestartcode")->nullable();
            $table->string("hgender")->nullable();
            $table->string("color")->nullable();
            $table->string("yob")->nullable();
            $table->string("breed")->nullable();
            $table->string("microchip")->nullable();
            $table->string("horigin")->nullable();
            $table->string("rgender")->nullable();
            $table->string("rcountry")->nullable();
            $table->string("rfname")->nullable();
            $table->string("rlname")->nullable();
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
        Schema::dropIfExists('lentries');
    }
}
