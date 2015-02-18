<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDebtTransactionMaterialAddStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('debt_transaction_material', function($table) {
            $table->tinyInteger('status')->unsigned()->default(0)->after('desc');
            $table->integer('op_uid')->unsigned()->default(0)->after('status');
            $table->string('op_user_name', 16)->default('')->after('op_uid');
            $table->date('op_date')->default('0000-00-00')->after('op_user_name');
        });
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('debt_transaction_material', function($table) {
            $table->dropColumn('status');
            $table->dropColumn('op_uid');
            $table->dropColumn('op_user_name');
            $table->dropColumn('op_date');
        });
	}

}
