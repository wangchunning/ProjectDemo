<?php

use Illuminate\Database\Migrations\Migration;

class UpdateBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// update BSB
		DB::statement("ALTER TABLE `banks` CHANGE `BSB` `BSB` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ");

		// add columns
		Schema::table('banks', function($table) 
		{
			$table->string('country', 60)->default('Australia')->after('swift_code');
			$table->string('limit', 60)->nullable()->after('currency');
			$table->string('range', 60)->nullable()->after('currency');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('banks', function($table)
		{
    		$table->dropColumn('country');
    		$table->dropColumn('limit');
    		$table->dropColumn('range');
		});
	}

}