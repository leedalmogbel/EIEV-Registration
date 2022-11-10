<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('name', 255)->index();
            $table->string('location', 255)->index();
            $table->text('description');
            $table->json('image')->nullable();
            $table->json('file')->nullable();
            $table->json('metadata')->nullable();
            $table->string('slug', 255)->index();
            $table->smallInteger('active')->default(1)->index();
            $table->string('season_id')->index();
            $table->enum('status', ['A', 'R', 'P'])->default('P')->index();
            $table->string('country', 10)->index();
            $table->integer('user_id')->index();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
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
        Schema::dropIfExists('events');
    }
}
