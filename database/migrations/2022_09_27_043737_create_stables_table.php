<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stables', function (Blueprint $table) {
            $table->id('stable_id');
            $table->string('name')->index();
            $table->enum('status', ['P', 'A', 'R'])->defualt('P')->index();
            $table->json('metadata');
            $table->bigInteger('user_id')->index();
            $table->enum('type', ['P', 'S'])->default('P')->index();
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
        Schema::dropIfExists('stables');
    }
}
