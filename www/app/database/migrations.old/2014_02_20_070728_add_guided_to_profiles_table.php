<?php

use Illuminate\Database\Migrations\Migration;

class AddGuidedToProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('profiles', function($table) 
		{
			$table->tinyInteger('guided')->unsigned()->default(0)->after('status');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('profiles', function($table)
		{
    		$table->dropColumn('guided');
		});
	}

}