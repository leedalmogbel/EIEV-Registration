<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsertedUpdatedDeletedLentries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lentries', function (Blueprint $table) {
            //
            $table->boolean('inserted')->default(0);
            $table->boolean('updated')->default(0);
            $table->boolean('deletec')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lentries', function (Blueprint $table) {
            //
        });
    }
}
