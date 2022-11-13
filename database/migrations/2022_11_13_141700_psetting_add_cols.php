<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PsettingAddCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psettings', function (Blueprint $table) {
            //
            $table->longText('host')->nullable();
            $table->ipAddress('ipaddress')->nullable();
            $table->unsignedInteger('cooldown')->default(0);
            $table->boolean('allowed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psettings', function (Blueprint $table) {
            //
        });
    }
}
