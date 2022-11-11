<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->id();
            $table->string('latestupdate')->nullable();
            $table->string('isactive')->nullable();
            $table->string('email')->nullable();
            $table->string('userid')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('mobileno')->nullable();
            $table->string('bday')->nullable();
            $table->string('stableid')->nullable();
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
        Schema::dropIfExists('userprofiles');
    }
}
