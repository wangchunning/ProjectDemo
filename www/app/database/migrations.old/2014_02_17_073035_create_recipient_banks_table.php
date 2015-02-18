<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRecipientBanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 删除 recipients 表关于银行信息的字段
		Schema::table('recipients', function($table)
		{
		    $table->dropColumn('swift_code');
		    $table->dropColumn('bank_name');
		    $table->dropColumn('branch_name');
		    $table->dropColumn('account_bsb');
		    $table->dropColumn('account_number');
		    $table->dropColumn('country');
		});

		Schema::dropIfExists('recipient_banks');

        Schema::create('recipient_banks', function($table)
        {
			$table->increments('id');
			$table->integer('recipient_id')->unsigned();
			$table->string('swift_code', 120)->nullable();
			$table->string('bank_name', 120)->nullable();
			$table->string('branch_name', 120)->nullable();
			$table->string('account_bsb', 120)->nullable();
			$table->string('account_number', 120);
			$table->text('currency');

			$table->softDeletes();			
			$table->timestamps();

			$table->foreign('recipient_id')->references('id')->on('recipients');
        });	

		DB::statement("ALTER TABLE  `recipients` CHANGE  `phone_type`  `phone_type` ENUM(  'Mobile',  'Land Line' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'Mobile'");        	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recipient_banks');
	}

}