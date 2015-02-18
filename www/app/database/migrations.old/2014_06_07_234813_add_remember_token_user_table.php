<?php

use Illuminate\Database\Migrations\Migration;

class AddRememberTokenUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `users`" .
					"ADD COLUMN `remember_token` varchar(100) DEFAULT '' ";

		DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$_sql = "ALTER TABLE `users`" .
			"DROP COLUMN `remember_token`";

		DB::statement($_sql);
	}

}