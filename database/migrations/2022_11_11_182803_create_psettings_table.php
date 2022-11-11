<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psettings', function (Blueprint $table) {
            $table->id();
            $table->boolean('syncriders')->default(0);
            $table->boolean('synchorses')->default(0);
            $table->boolean('synctrainers')->default(0);
            $table->boolean('syncstables')->default(0);
            $table->boolean('syncowners')->default(0);
            $table->boolean('syncentries')->default(0);
            $table->boolean('syncevents')->default(0);
            $table->boolean('syncprofiles')->default(0);
            $table->boolean('syncall')->default(0);
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
        Schema::dropIfExists('psettings');
    }
}
