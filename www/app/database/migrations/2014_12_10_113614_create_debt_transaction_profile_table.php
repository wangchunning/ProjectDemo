<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtTransactionProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `debt_transaction` " .
				"drop column `desc`,".
				"drop column `reason`,".
				"CHANGE `title` `title` char(14) NOT NULL DEFAULT '',".
				"CHANGE `user_name` `user_name` char(16) NOT NULL DEFAULT ''"
		;
		DB::statement($_sql);
		
		Schema::dropIfExists('debt_transaction_profile');
		
        Schema::create('debt_transaction_profile', function($table)
        {
        	$table->increments('tx_id');
			$table->string('company_name', 50)->default('');
			$table->string('purpose', 50)->default('');	
			$table->string('repayment_from', 50)->default('');
			$table->string('state', 20)->default('');
			$table->string('city', 20)->default('');
			$table->date('register_date')->default('0000-00-00');	
			$table->decimal('register_capital', 10, 2)->unsinged()->default(0);	// 万元
			$table->decimal('property_value', 10, 2)->unsinged()->default(0);	// 万元
			$table->decimal('last_year_income', 10, 2)->unsinged()->default(0);		// 万元
			$table->string('industry', 30)->default('');	// 行业
			$table->string('operation_info', 200)->default('');
			$table->string('break_law_info', 200)->default('');
			$table->string('credit_info', 200)->default('');
			$table->string('guarantee_range', 20)->default('');
			$table->string('pawn', 50)->default('');
			$table->string('guarantee_info', 200)->default('');
			$table->text('desc');
			
			$table->timestamps();
			
			$table->engine = 'InnoDB';
			
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('debt_transaction_profile');
	}

}
