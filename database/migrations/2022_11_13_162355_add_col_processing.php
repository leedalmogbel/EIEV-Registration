<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColProcessing extends Migration
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
            $table->dropColumn('processing');
            $table->boolean('processing_riders')->default(0);
            $table->boolean('processing_horses')->default(0);
            $table->boolean('processing_trainers')->default(0);
            $table->boolean('processing_stables')->default(0);
            $table->boolean('processing_owners')->default(0);
            $table->boolean('processing_entries')->default(0);
            $table->boolean('processing_events')->default(0);
            $table->boolean('processing_profiles')->default(0);
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
