<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email', 255)->unique();
            $table->string('username', 50)->unique();
            $table->text('password');
            $table->string('firstname');
            $table->string('lastname');
            $table->smallInteger('active')->default(1)->index();
            $table->string('emirates_id', 50)->index();
            $table->datetime('dob')->index();
            
            $table
                ->enum('status', ['P', 'R', 'A', 'S'])
                ->default('P')
                ->index();
            
            $table
                ->enum('discipline', ['E', 'D', 'F', 'J'])
                ->default('E')
                ->index();

            $table->json('documents')->nullable();
            
            $table
                ->string('elve_id', 50)
                ->nullable()
                ->index();

            $table
                ->string('eef_id', 50)
                ->nullable()
                ->index();

            $table
                ->string('fei_id', 50)
                ->nullable()
                ->index();

            $table
                ->string('stable_name')
                ->nullable()
                ->index();

            $table->integer('role')
                  ->default(0)
                  ->index();
            
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
        Schema::dropIfExists('users');
    }
}
