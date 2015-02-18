<?php

use Illuminate\Database\Migrations\Migration;


class UpdateRecipientsAgainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recipients', function($table)
		{
			$table->string('email', 128)->nullable()->after('uid');
    		$table->enum('phone_type', array('Mobile', 'Work', 'Home'))->default('Mobile')->after('phone_code');
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
	}

}