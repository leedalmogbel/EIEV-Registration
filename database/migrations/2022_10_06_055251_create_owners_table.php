<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->id('owner_id');
            $table->string('emiratesId')->index();
            $table->enum('discipline', ['E', 'S', 'D', 'V', 'R', 'T'])->index();
            $table->string('feiRegistrationNo')->index();
            $table->date('feiRegistrationDate');
            $table->enum('visaType', ['R', 'T', 'C'])->index();
            $table->enum('gender', ['F', 'M']);
            $table->string('firstname')->index();
            $table->string('lastname')->index();
            $table->date('dob')->nullable();
            $table->string('nationality');
            $table->json('uaeAddress')->nullable();
            $table->json('homeAddress')->nullable();
            $table->json('document')->nullable();
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('user_id')->index();
            $table->text('remarks')->nullable()->default('');
            $table->enum('status', ['P', 'A', 'R'])->default('P')->index();
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
        Schema::dropIfExists('owners');
    }
}
