<?php

use Illuminate\Database\Migrations\Migration;

class AddRegCountryToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table) {
            $table->string('reg_country', 64)->nullable();
            $table->string('reg_city', 64)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table) {
            $table->dropColumn('reg_country');
            $table->dropColumn('reg_city');
        });
	}

}