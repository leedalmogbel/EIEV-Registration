<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id('media_id');
            $table->string('email', 255)->unique()->nullable();
            $table->string('username', 50)->unique()->nullable();
            $table->text('password')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('mobile')->nullable();
            $table->string('company')->nullable();
            $table->string('emirates_id', 50)->index()->nullable();

            $table->smallInteger('active')->default(1)->index();
            $table
                ->enum('status', ['P', 'R', 'A', 'S'])
                ->default('P')
                ->index();

            $table->string('photo')->nullable();
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
        Schema::dropIfExists('media');
    }
}
