<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('administrators');

        Schema::create('administrators', function($table)
        {
            // Table columns
            $table->increments('uid');
            $table->integer('parent_uid')->unsigned()->default(0);
            $table->char('user_name', 16)->default('');
            $table->char('real_name', 6)->default('');       
            $table->char('password', 64)->default('');   
            $table->char('login_session', 40)->default('');
            $table->tinyInteger('has_reset_password')->unsigned()->default(0);  
            $table->tinyInteger('status')->unsigned()->default(0);
            
            $table->timestamps();

            // Table indexes
            $table->index('parent_uid');
            $table->unique('user_name');
            $table->unique('real_name');

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
		Schema::drop('administrators');
	}

}
