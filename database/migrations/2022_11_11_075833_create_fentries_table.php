<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFentriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fentries', function (Blueprint $table) {
            $table->id();
            $table->string("horsename")->nullable();
            $table->string("horsenfid")->nullable();
            $table->string("horsefeiid")->nullable();
            $table->string("ridername")->nullable();
            $table->string("ridernfid")->nullable();
            $table->string("riderfeiid")->nullable();
            $table->string("trainername")->nullable();
            $table->string("trainernfid")->nullable();
            $table->string("trainerfeiid")->nullable();
            $table->string("ownername")->nullable();
            $table->string("stablename")->nullable();
            $table->string("eventcode")->nullable();
            $table->string("classcode")->nullable();
            $table->string("code")->nullable();
            $table->string("userid")->nullable();
            $table->string("riderid")->nullable();
            $table->string("riderstableid")->nullable();
            $table->string("horseid")->nullable();
            $table->string("stableid")->nullable();
            $table->string("trainerid")->nullable();
            $table->string("ownerid")->nullable();
            $table->string("isfetched")->nullable();
            $table->string("status")->nullable();
            $table->string("remarks")->nullable();
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
            $table->string("review")->nullable();
            $table->string("paytype")->nullable();
            $table->string("posted")->nullable();
            $table->string("chargeable")->nullable();
            $table->string("parentid")->nullable();
            $table->string("withdrawdate")->nullable();
            $table->string("substidate")->nullable();
            $table->string("docsuploaded")->nullable();
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
        Schema::dropIfExists('fentries');
    }
}
