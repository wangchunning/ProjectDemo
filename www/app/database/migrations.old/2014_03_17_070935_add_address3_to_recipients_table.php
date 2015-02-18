<?php

use Illuminate\Database\Migrations\Migration;

class AddAddress3ToRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recipients', function($table) 
		{

			$table->string('address_3', 60)->default('')->after('address_2');
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
    		$table->dropColumn('address_3');
		});
	}

}