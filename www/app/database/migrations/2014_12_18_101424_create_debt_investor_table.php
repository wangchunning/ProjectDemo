<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtInvestorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::dropIfExists('debt_investor');
		
        Schema::create('debt_investor', function($table)
        {
        	$table->increments('id');
			$table->char('receipt_number', 14)->default('');
			$table->integer('tx_id')->unsigned()->default(0);	
			$table->tinyInteger('tx_period')->unsigned()->default(0);
			$table->char('tx_title', 14)->default('');
			$table->integer('investor_uid')->unsigned()->default(0);
			$table->char('investor_user_name', 16)->default('');
			$table->decimal('invest_amount', 12, 2)->unsinged()->default(0);	// 
			$table->decimal('interest_amount', 10, 2)->unsinged()->default(0);	// 
			$table->decimal('paid_interest_amount', 10, 2)->unsinged()->default(0);		// 
			$table->decimal('unpaid_principal_amount', 12, 2)->unsinged()->default(0);
			
			$table->timestamps();
			
			$table->index('tx_id');
			$table->index('investor_uid');
			
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
		Schema::drop('debt_investor');
	}

}
