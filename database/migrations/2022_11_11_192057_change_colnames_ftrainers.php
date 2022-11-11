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
            $table->string('nfx0020license')->nullable();
            $table->string('firstx0020name')->nullable();
            $table->string('familyx0020name')->nullable();
            $table->string('nationalityshort')->nullable();
            $table->string('feix0020reg')->nullable();
            $table->string('registeredx0020season')->nullable();
            $table->string('countryshort')->nullable();
            $table->string('homecountryshort')->nullable();
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
