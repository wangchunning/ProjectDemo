<?php

use Illuminate\Database\Migrations\Migration;

class CreateBillingDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::dropIfExists('billing_detail');

		Schema::create('billing_detail', function($table)
		{
			$table->increments('id');
			$table->string('bill_id', 15);
			$table->string('currency_from', 5)->default('');
			$table->string('currency_to', 5)->default('');
			$table->string('converted_currency_from', 5)->default('');
			$table->string('converted_currency_to', 5)->default('');
			$table->decimal('rate', 12, 6)->unsigned()->default(0);
			$table->decimal('bank_buy_amount', 12, 2)->default(0);
			$table->decimal('bank_sell_amount', 12, 2)->default(0);
			$table->decimal('bank_converted_buy_amount', 12, 2)->default(0);
			$table->decimal('bank_converted_sell_amount', 12, 2)->default(0);
			$table->timestamps();
			$table->softDeletes();

			$table->index('bill_id');
			$table->index('currency_from');
			$table->index('currency_to');
			$table->index('created_at');
			$table->foreign('bill_id')->references('id')->on('billings');

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
		//
		Schema::drop('billing_detail');
	}

}
