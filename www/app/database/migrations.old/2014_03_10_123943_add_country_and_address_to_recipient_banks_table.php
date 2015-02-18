<?php

use Illuminate\Database\Migrations\Migration;

class AddCountryAndAddressToRecipientBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recipient_banks', function($table) 
		{
			$table->string('country', 120)->nullable()->after('swift_code');
			$table->string('bank_address')->nullable()->after('branch_name');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recipient_banks', function($table)
		{
    		$table->dropColumn('country');
    		$table->dropColumn('bank_address');
		});
	}

}