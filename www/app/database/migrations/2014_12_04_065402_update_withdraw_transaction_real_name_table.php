<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWithdrawTransactionRealNameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('withdraw_transaction', function($table) {
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
		Schema::table('withdraw_transaction', function($table) {
            $table->dropColumn('account_name');
        });
	}

}
