<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function($table)
        {
            // Table columns
            $table->increments('uid');
            $table->enum('type', array('user', 'manager'))->default('user');            
            $table->string('email', 128);
            $table->string('password', 64);   
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('reg_ip', 45)->default('0.0.0.0');
            $table->timestamp('reg_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Table indexes
            $table->unique('email');

            $table->engine = 'InnoDB';

        });		
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('users');
    }

}