<?php

use Illuminate\Database\Migrations\Migration;

class CreateBusinessInviteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('business_invite', function($table)
        {
			$table->increments('id');
			$table->string('email', 128);
			$table->string('invite_token');
			$table->timestamp('expires');
			$table->integer('invitor_id')->unsigned();
			$table->integer('invitee_id')->unsigned();
			
			$table->timestamps();

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
		//
		Schema::drop('business_invite');
	}

}