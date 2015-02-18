<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('debt_transaction');
		
        Schema::create('debt_transaction', function($table)
        {
        	$table->increments('id');
			$table->integer('uid')->unsigned();
			$table->string('user_name', 16)->default('');
			$table->char('receipt_number', 14)->default('');
			$table->string('title', 14)->default('');
			$table->string('reason', 20)->default('');	
			$table->decimal('total_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('available_amount', 12, 2)->unsinged()->default(0);
			$table->decimal('frozen_amount', 10, 2)->unsinged()->default(0);
			$table->decimal('fee_amount', 10, 2)->unsinged()->default(0);	
			$table->tinyInteger('period_month')->unsigned()->default(0);
			$table->decimal('rate', 5, 2)->unsinged()->default(0);
			$table->decimal('repayment_in_advance_rate', 5, 2)->unsinged()->default(0);
			$table->tinyInteger('repayment_type')->unsigned()->default(0);
			$table->decimal('month_repayment_total_amount', 10, 2)->unsinged()->default(0);
			$table->decimal('month_repayment_fee_amount', 10, 2)->unsinged()->default(0);
			$table->decimal('month_repayment_investor_amount', 10, 2)->unsinged()->default(0);
			$table->date('validate_date')->default('0000-00-00');
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->text('desc');
			
			$table->timestamps();

			$table->index('uid');
			$table->index('user_name');

			$table->index('created_at');
			
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
		Schema::drop('debt_transaction');
	}

}
