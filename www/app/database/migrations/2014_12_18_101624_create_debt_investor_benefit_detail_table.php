<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtInvestorBenefitDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::dropIfExists('debt_investor_benefit_detail');
		
        Schema::create('debt_investor_benefit_detail', function($table)
        {
        	$table->increments('id');
			$table->integer('tx_id')->unsigned()->default(0);	
			$table->integer('investor_uid')->unsigned()->default(0);
			$table->integer('debt_investor_id')->unsigned()->default(0);
			$table->tinyInteger('period_cnt')->unsigned()->default(0);
			$table->decimal('total_amount', 12, 2)->unsinged()->default(0);	// 
			$table->decimal('principal_amount', 12, 2)->unsinged()->default(0);	// 
			$table->decimal('interest_amount', 10, 2)->unsinged()->default(0);		// 
			$table->decimal('unpaid_total_amount', 12, 2)->unsinged()->default(0);		//
			
			$table->date('expect_repayment_date')->default('0000-00-00');
			$table->date('repayment_date')->default('0000-00-00');
			
			$table->tinyInteger('status')->unsigned()->default(0);
			
			$table->timestamps();
			
			$table->index('tx_id');
			$table->index('investor_uid');
			$table->index('debt_investor_id');
			
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
		Schema::drop('debt_investor_benefit_detail');
	}

}
