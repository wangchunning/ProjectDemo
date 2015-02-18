<?php

use Illuminate\Database\Migrations\Migration;

class UpdateRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recipients', function($table)
		{
    		$table->string('phone_code', 10)->after('postcode');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recipients', function($table)
		{
    		$table->dropColumn('phone_code');
		});
	}

}