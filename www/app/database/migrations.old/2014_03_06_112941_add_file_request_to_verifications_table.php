<?php

use Illuminate\Database\Migrations\Migration;

class AddFileRequestToVerificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('verifications', function($table) 
		{
			$table->string('photo_id_request', 15)->nullable()->after('photo_id_file');
			$table->string('addr_proof_request', 15)->nullable()->after('addr_proof_file');
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
    		$table->dropColumn('photo_id_request');
    		$table->dropColumn('addr_proof_request');
		});
	}

}