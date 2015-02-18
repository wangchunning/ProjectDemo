<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('RoleSeeder');
		$this->call('UserSeeder');
		$this->call('BankSeeder');
		$this->call('BasicRateSeeder');		
		$this->call('SwiftCodeSeeder');
	}

}