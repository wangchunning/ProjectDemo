<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserInvite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('user_invite');

        Schema::create('user_invite', function($table)
        {

			$table->increments('id');
			$table->string('email', 128);
			$table->string('invite_token');
			$table->timestamp('expires');
			$table->string('roles', 128)->nullable();
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
		Schema::drop('user_invite');
	}

}