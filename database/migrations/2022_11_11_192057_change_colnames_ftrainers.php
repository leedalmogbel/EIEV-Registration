<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColnamesFtrainers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ftrainers', function (Blueprint $table) {
            //
            $table->dropColumn('nf_x0020_license');
            $table->dropColumn('first_x0020_name');
            $table->dropColumn('family_x0020_name');
            $table->dropColumn('nationality_short');
            $table->dropColumn('fei_x0020_reg');
            $table->dropColumn('registered_x0020_season');
            $table->dropColumn('country_short');
            $table->dropColumn('homecountry_short');
            $table->string('nf_x0020_license')->nullable();
            $table->string('first_x0020_name')->nullable();
            $table->string('family_x0020_name')->nullable();
            $table->string('nationality_short')->nullable();
            $table->string('fei_x0020_reg')->nullable();
            $table->string('registered_x0020_season')->nullable();
            $table->string('country_short')->nullable();
            $table->string('homecountry_short')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ftrainers', function (Blueprint $table) {
            //
        });
    }
}
