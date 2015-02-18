<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtTransactionMaterialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('debt_transaction_material');
		
        Schema::create('debt_transaction_material', function($table)
        {
        	$table->increments('id');
			$table->integer('tx_id')->unsigned()->default(0);
			$table->tinyInteger('type')->unsigned()->default(0);	
			$table->string('name', 50)->default('');
			$table->string('path', 64)->default('');
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
		Schema::drop('debt_transaction_material');
	}

}
