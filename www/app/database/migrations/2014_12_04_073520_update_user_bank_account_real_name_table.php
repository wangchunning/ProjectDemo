<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBankAccountRealNameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_bank_account', function($table) {
            $table->string('account_name', 6)->default('')->after('bank_state');
        });
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_bank_account', function($table) {
            $table->dropColumn('account_name');
        });
	}

}
