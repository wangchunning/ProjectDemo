<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDebtTransactionAddInvestAmountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('debt_transaction', function($table) {
            $table->decimal('invest_amount', 12, 2)->unsigned()->default(0)->after('fee_amount');
        });
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('debt_transaction', function($table) {
            $table->dropColumn('invest_amount');
        });
	}
	

}
