<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTrustCreditLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('user_trust_credit_log');
        
        Schema::create('user_trust_credit_log', function($table)
        {
            // Table columns
        	$table->increments('id');
            $table->integer('uid')->unsigned()->default(0);
            $table->smallInteger('trust_credit_balance')->default(0);
            $table->smallInteger('amount')->default(0);
            $table->text('desc');

            $table->timestamps();

            // Table indexes
            $table->index('uid');
            
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
		Schema::drop('user_trust_credit_log');
	}

}
