<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCdCols extends Migration
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
            $table->dropColumn('cooldown');
            $table->unsignedInteger('cooldown_riders')->default(0);
            $table->unsignedInteger('cooldown_horses')->default(0);
            $table->unsignedInteger('cooldown_trainers')->default(0);
            $table->unsignedInteger('cooldown_stables')->default(0);
            $table->unsignedInteger('cooldown_owners')->default(0);
            $table->unsignedInteger('cooldown_entries')->default(0);
            $table->unsignedInteger('cooldown_events')->default(0);
            $table->unsignedInteger('cooldown_profiles')->default(0);
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
