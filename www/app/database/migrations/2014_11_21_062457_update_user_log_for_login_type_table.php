<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserLogForLoginTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_access_log', function($table) {
            $table->TinyInteger('login_from')->default(0)->after('user_name');
        });
		
		Schema::table('user_login_log', function($table) {
			$table->TinyInteger('login_from')->default(0)->after('user_name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_access_log', function($table) {
            $table->dropColumn('login_from');
        });
		
		Schema::table('user_login_log', function($table) {
			$table->dropColumn('login_from');
		});
	}

}
