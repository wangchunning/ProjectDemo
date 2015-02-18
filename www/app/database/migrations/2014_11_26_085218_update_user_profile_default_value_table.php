<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProfileDefaultValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('user_profile');

        Schema::create('user_profile', function($table)
        {
            $table->integer('uid')->unsigned();
            
            $table->char('id_number', 18)->default('');
            $table->char('real_name', 6)->default('');
            $table->date('birthday')->default('0000-00-00');
            $table->tinyInteger('gender')->unsigned()->default(0);
            
            $table->tinyInteger('education')->unsigned()->default(0);
            $table->smallInteger('education_entry_year')->unsigned()->default(0);
            $table->string('education_school', 32)->default('');
            
            $table->string('hukou_city', 10)->default('');
            $table->string('hukou_state', 10)->default('');
            $table->string('address', 64)->default('');
            $table->integer('postcode')->unsigned()->default(0);
            
            $table->tinyInteger('marriage')->unsigned()->default(0);
            $table->tinyInteger('has_child')->unsigned()->default(0);
            
            $table->char('relative_name', 6)->default('');
            $table->string('relative_relationship', 10)->default('');
            $table->char('relative_mobile', 16)->default('');
            $table->char('other_contactor_name', 6)->default('');
            $table->string('other_contactor_relationship', 10)->default('');
            $table->char('other_contrator_mobile', 16)->default('');
            
            $table->string('company_name', 64)->default('');
            $table->string('position', 16)->default('');
            $table->tinyInteger('salary')->unsigned()->default(0);
            $table->string('work_email', 40)->default('');
            $table->string('company_city', 10)->default('');
            $table->string('company_state', 10)->default('');
            $table->string('company_address', 64)->default('');
            $table->string('company_type', 32)->default('');
            $table->string('company_category', 32)->default('');
            $table->tinyInteger('work_year_cnt')->unsigned()->default(0);
            $table->string('company_telephone_code', 10)->default('');
            $table->string('company_telephone_number', 18)->default('');

            $table->tinyInteger('has_house')->unsigned()->default(0);
            $table->tinyInteger('has_mortgage')->unsigned()->default(0);
            $table->tinyInteger('has_car')->unsigned()->default(0);
            
            $table->timestamps();

            // Table indexes
            $table->primary('uid');
            $table->unique('id_number');
            
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
		//Schema::drop('user_profile');
	}

}
