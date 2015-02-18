<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBankAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('user_bank_account');
        
        Schema::create('user_bank_account', function($table)
        {
            // Table columns
            $table->integer('uid')->unsigned();

            $table->string('bank_name', 30)->default('');
            $table->string('bank_full_name', 50)->default('');            
            $table->string('bank_city', 20)->default('');
            $table->string('bank_state', 10)->default('');           
			$table->bigInteger('account_number')->unsinged()->default(0);
			
            $table->timestamps();

            // Table indexes
            $table->primary('uid');
            
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
		Schema::drop('user_bank_account');
	}

}
