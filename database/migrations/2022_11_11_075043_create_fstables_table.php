<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFstablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fstables', function (Blueprint $table) {
            $table->id();
            $table->string("lastestupdate")->nullable();
            $table->string("stableid")->unique();
            $table->string("name")->nullable();
            $table->string("address")->nullable();
            $table->string("zip")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("remarks")->nullable();
            $table->string("owner")->nullable();
            $table->string("discipline")->nullable();
            $table->string("category")->nullable();
            $table->string("division")->nullable();
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
        Schema::dropIfExists('fstables');
    }
}
