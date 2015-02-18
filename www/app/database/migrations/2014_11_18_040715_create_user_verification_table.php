<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVerificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('user_verification');

        Schema::create('user_verification', function($table)
        {
            // Table columns
        	$table->integer('uid')->unsigned();
        	$table->string('email_verify_token', 120)->default('');

            $table->timestamps();

            // Table indexes
            $table->primary('uid');

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
		Schema::drop('user_verification');
	}

}
