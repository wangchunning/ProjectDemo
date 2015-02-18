<?php

use Illuminate\Database\Migrations\Migration;

class AddAddressDetailToSwiftcodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('swift_code', function($table) 
		{
			$table->string('unit_number', 60)->default('')->after('address');
			$table->string('street_number', 60)->default('')->after('address');
			$table->string('street', 60)->default('')->after('address');
			$table->string('state', 60)->default('')->after('address');
			$table->string('postcode', 30)->default('')->after('address');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('swift_code', function($table)
		{
    		$table->dropColumn('unit_number');
    		$table->dropColumn('street_number');
    		$table->dropColumn('street');
    		$table->dropColumn('state');
    		$table->dropColumn('postcode');
		});
	}

}