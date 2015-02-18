<?php

use Illuminate\Database\Migrations\Migration;

class AddPhoneCodeToVerificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('verifications', function($table) 
		{
            $table->string('phone_code', 10)->after('addr_proof_file')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('verifications', function($table)
		{
    		$table->dropColumn('phone_code');
		});
	}

}