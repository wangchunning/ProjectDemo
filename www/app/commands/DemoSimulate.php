<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class DemoSimulate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'demo:simulate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate test data ONLY for demo.wexchange.com';


	public static $_data_prefix = '_sim';

	public static $_create_user_cnt = 80;
	public static $_create_recipient_cnt = 60;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		//
		$_del = $this->option('delete');
		if ($this->option('delete'))
		{
			$this->_rollback();
			return;
		}

		try 
		{

			/**
			 * all SQL must be in a transaction
			 */
			/* 
			 * begin MySQL transaction 
			 */
			$this->info('Begin Transaction...');			
			DB::connection()->getPdo()->beginTransaction();

			/**
			 * create Users
			 */
			$this->info('create Users...');			
			$_uids = $this->_createUsers();

			/**
			 * create user's data, such as balance, receipts, tx ...
			 */
			/**
			 * create user balance
			 */
			$this->info('create Balance...');				
			$this->_createBalance($_uids);
			/**
			 * create user recipients
			 */
			$this->info('create Recipients...');	
			$this->_createRecipients($_uids);

			/**
			 * create user receipts
			 */
			$this->info('create Receipts...');	
			$this->_createReceipts($_uids);

			/**
			 * create user tx
			 */
			$this->info('create Transactions...');	
			$this->_createTransactions($_uids);

			/* 
			 * commit MySQL transaction
			 */
			$this->info('Transaction Successfully.');	
			DB::connection()->getPdo()->commit();

		} 
		catch (\PDOException $e)
		{
			$this->error("Simulation rolls back.");
			$this->error($e->getMessage());
			/* rollback */
			DB::connection()->getPdo()->rollBack();

		}



		return;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('delete', 'd', InputOption::VALUE_NONE, 
					'Delete all test data generated from last time.', null),
		);
	}

	/**
	 * 1. 生成用户 (Customer) 注册信息，生成策略：
	 * 1）用户类型：Personal, Business
	 * 2）用户状态：Pending, Active
	 * 3）生成策略：每种状态各10个，每种类型各30个，共计80个注册用户
	 * 
	 * 相关表: users, profiles, business, verifications, user_logs
	 *
	 * @return [Array(uid)] - 新 User 的 uid 数组
	 */
	private function _createUsers()
	{
		$_uid_arr = array();
		$_created_at = Carbon::now()->toDateTimeString();

		$_profile_type = array('personal', 'business');
		$_user_cnt = DemoSimulate::$_create_user_cnt / count($_profile_type);		

		foreach ($_profile_type as $_ptype)
		{
			for ($_idx = 0; $_idx < $_user_cnt; $_idx++)
			{
				/**
				 * Personal, Business
				 */	
				$_user = array();		
		        $_user['email'] = sprintf("%s_email_%d@wexchange.com", DemoSimulate::$_data_prefix, rand());
		        $_user['password'] = Hash::make('123456');
		        $_user['first_name'] = sprintf("%s_fn_%d", DemoSimulate::$_data_prefix, rand());;
		        $_user['last_name'] = sprintf("%s_ln_%d", DemoSimulate::$_data_prefix, rand());;		
		        $_user['reg_at'] = $_created_at;

				$_uid = DB::table('users')->insertGetId($_user);

				$_profile = array();
				$_profile['uid'] = $_uid;
		        $_profile['type'] = $_ptype;
		        $_profile['street'] = DemoSimulate::$_data_prefix;
				$_profile['created_at'] = $_created_at;
				$_profile['updated_at'] = $_created_at;

				/**
				 * verification
				 */
				$_verify = array();
				$_verify['uid'] = $_uid;
				$_verify['created_at'] = $_created_at;
				$_verify['updated_at'] = $_created_at;
				if ($_idx > $_user_cnt / 2)
				{

					$_verify['email_verified'] = 1;
					$_verify['photo_id_verified'] = 1;
					$_verify['addr_proof_verified'] = 1;
					$_verify['security_verified'] = 1;

					$_profile['status'] = 'verified';

				}

		        DB::table('profiles')->insert($_profile);
				DB::table('verifications')->insert($_verify);

				$_uid_arr[] = $_uid;
			}

		}


		return $_uid_arr;
	}

	/**
     * 2. 生成 User 账户余额，生成策略：
     * 1）相关货币：所有货币（10种）
     * 2）生成策略：所有1.中Active账户，余额+1000
     *
     * 相关表: balance, balance_history, user_logs
     * 
	 * @param  $user - User 对象 
	 * @return void
	 */
	private function _createBalance($uids)
	{
		$_currencies = array('AUD', 'CAD', 'CNY', 'EUR', 'GBP', 'HKD', 'JPY', 'NZD', 'SGD', 'USD');
		$_created_at = Carbon::now()->toDateTimeString();

		foreach ($uids as $_uid)
		{
			$_profile = Profile::where('uid', $_uid)->first();

			if ($_profile->status != 'verified')
			{
				continue;
			}

			foreach($_currencies as $_currency)
			{
				$_balance = array();
				$_balance['uid'] = $_uid;
				$_balance['currency'] = $_currency;
				$_balance['amount'] = 1000;
				$_balance['frozen_amount'] = 0;
				$_balance['created_at'] = $_created_at;
				$_balance['updated_at'] = $_created_at;

				DB::table('balances')->insert($_balance);				
			}

		}

		return;
	}

	/**
	 * 3. 生成 Receipt，生成策略： 
	 * 1）相关用户：1.中 Active 的用户
	 * 2）相关货币：所有货币对
	 * 3）生成策略：
	 *      a) 每个用户3种类型操作各1个。即，只存款，只取款，存款+换汇，换汇+取款，存款+换汇+取款
	 *      b) 金额范围 [200, 1000]
	 *
	 * 相关表: receipts, transfer_history, user_logs
	 * 
	 * @param  $user - User 对象 
	 * @return void
	 */	
	private function _createReceipts($uids)
	{
		$_currencies = array('AUD', 'CAD', 'CNY', 'EUR', 'GBP', 'HKD', 'JPY', 'NZD', 'SGD', 'USD');
		$_created_at = Carbon::now()->toDateTimeString();

		foreach ($uids as $_uid)
		{
			$_profile = Profile::where('uid', $_uid)->first();

			if ($_profile->status != 'verified')
			{
				continue;
			}

			foreach($_currencies as $_currency)
			{
				/* add fund */
				$_amount = rand(100, 500);
				$_rid = sprintf('R%sD', rand(201401010000, 201402020001));
				$_data = array(
						"transfer_to" => "balance",
						"payment_method" => "deposit",
						"deposit" => array(
									"amount" => $_amount,
									"currency" => $_currency,
									)
						);
				$_receipt = array();
				$_receipt['id'] = $_rid;
				$_receipt['uid'] = $_uid;
				$_receipt['type'] = 'Deposit';
				$_receipt['status'] = 'Waiting for fund';
				$_receipt['data'] = serialize($_data);
				$_receipt['created_at'] = $_created_at;
				$_receipt['updated_at'] = $_created_at;

		        DB::table('receipts')->insert($_receipt);

		        $_tx = array();

		        $_data = array(
							"amount" => $_amount,
							"currency" => $_currency,
		        		);
            	$_tx['id'] = sprintf('D%s', rand(201401010000, 201402020001));
           		$_tx['uid'] = $_uid;
          		$_tx['type'] = 'Deposit';
        		$_tx['status'] = 'Waiting for fund';
        		$_tx['amount'] = $_amount;
				$_tx['currency_from'] = $_currency;
   				$_tx['currency_to'] = $_currency;
      			$_tx['currency'] = $_currency;
          		$_tx['rate']  = 0;
          		$_tx['data'] = serialize($_data);
    			$_tx['receipt_id'] = $_rid;
				$_tx['created_at'] = $_created_at;
				$_tx['updated_at'] = $_created_at;

				DB::table('transactions')->insert($_tx);

		        /* withdraw */
		        $_amount = rand(100, 500);
		        $_recipient = array(
		        					'id'	=> 1,
									'transfer_country' => 'China',
									'swift_code' => 'ANZBAU3MCLS',
									'bank_name' => 'ANZ',
									'branch_name' => 'anz',
									'account_bsb' => '123456',
									'account_number' => '21321321',
									'account_name' => 'test_name',
									'save_to_book' => 0,
									'address' => 'test_address',
									'address_2' => 'test_address2',
									'postcode' =>'21321',
									'country' => 'China',
									'phone_code' => '86',
									'phone' => '21321321',
									'reason' => 'Travel',
									'message' => '',
									'send_sms' => 0,
							);

				$_data = array(
						"currency" => $_currency,
						"amount" => $_amount,
						"transfer_to" => "new",
						'recipient' => $_recipient,
						"payment_method" => "balance",
						"send_sms"	=> 0,
						"withdraw" => array(
									"amount" => $_amount,
									"currency" => $_currency,
									'recipient' => $_recipient,
									),
						'fee' => array(
													'total' => 20,
													'items' => array(
														array(
															'name' => 'Transaction Fee',
															'amount' => '20',
															'fee' => 20,
														),
													),

											),	
						'reason' => 'Travel',
						'message' => '',						
						);
		        $_rid = sprintf('R%sW', rand(201401010000, 201402020001));
				$_receipt = array();
				$_receipt['id'] = $_rid;
				$_receipt['uid'] = $_uid;
				$_receipt['type'] = 'Withdraw';
				$_receipt['status'] = 'Processing';
				$_receipt['data'] = serialize($_data);
				$_receipt['created_at'] = $_created_at;
				$_receipt['updated_at'] = $_created_at;

		        DB::table('receipts')->insert($_receipt);		        

		        $_tx = array();
		        $_data = array(
							"amount" => $_amount,
							"currency" => $_currency,
							'recipient' => $_recipient,	
							"send_sms"	=> 0,
							'fee' => array(
										'total' => 20,
										'items' => array(
												'name' => 'Transaction Fee',
												'amount' => '20',
												'fee' => 20,
											),

								),						
		        		);		        
            	$_tx['id'] = sprintf('W%s', rand(201401010000, 201402020001));
           		$_tx['uid'] = $_uid;
          		$_tx['type'] = 'Withdraw';
        		$_tx['status'] = 'Pending';
        		$_tx['amount'] = $_amount;
				$_tx['currency_from'] = $_currency;
   				$_tx['currency_to'] = $_currency;
      			$_tx['currency'] = $_currency;
          		$_tx['rate']  = 0;
          		$_tx['data'] = serialize($_data);
    			$_tx['receipt_id'] = $_rid;
				$_tx['created_at'] = $_created_at;
				$_tx['updated_at'] = $_created_at;

				DB::table('transactions')->insert($_tx);	        

		        /* fx */
				$_receipt = array();
				$_currency_to = $_currencies[rand(0,9)];
				while (1)
				{
					if ($_currency_to == $_currency)
					{
						$_currency_to = $_currencies[rand(0,9)];
						continue;
					}			
					break;		
				}

				$_rate = Exchange::query($_currency.$_currency_to, 'manager');
				$_rate = $_rate[0]['Bid'];
				$_amount = rand(100, 300);
				$_data = array(
					"transfer_to" => "balance",
					"recipient" => '',
					"reason" => "Transportation",
					"message" => '',
					"send_sms" => "1",
					"payment_method" => "balance",
					"payment" => '',
					"fx" => array(
						"lock_currency_have" => $_currency,
						"lock_currency_want" => $_currency_to,
						"lock_currency" => $_currency.$_currency_to, 
						"lock_rate" => $_rate,
						"lock_amount" => $_amount,
						),
					);
				$_receipt['id'] = sprintf('R%sF', rand(201401010000, 201402020001));
				$_receipt['uid'] = $_uid;
				$_receipt['type'] = 'FX Deal';
				$_receipt['status'] = 'Completed';
				$_receipt['created_at'] = $_created_at;
				$_receipt['updated_at'] = $_created_at;

		        DB::table('receipts')->insert($_receipt);		        

		        $_tx = array();
				$_data = array(
					"lock_currency_have" => $_currency,
					"lock_currency_want" => $_currency_to,
					"lock_currency" => $_currency.$_currency_to, 
					"lock_rate" => $_rate,
					"lock_amount" => $_amount,
				);		        
            	$_tx['id'] = sprintf('F%s', rand(201401010000, 201402020001));
           		$_tx['uid'] = $_uid;
          		$_tx['type'] = 'Fx Deal';
        		$_tx['status'] = 'Completed';
        		$_tx['amount'] = $_amount;
				$_tx['currency_from'] = $_currency;
   				$_tx['currency_to'] = $_currency_to;
      			$_tx['currency'] = $_currency;
          		$_tx['rate']  = $_rate;
    			$_tx['receipt_id'] = $_rid;
    			$_tx['data'] = serialize($_data);
				$_tx['created_at'] = $_created_at;
				$_tx['updated_at'] = $_created_at;

				DB::table('transactions')->insert($_tx);

		       
			}

		}

	
		return;
	}

	/**
	 * 3. 生成 Transaction，生成策略： 
	 * 1）Tx类型：Deposit, Fx Deal, Withdraw
	 * 2）相关用户：1.中 Active 的 Personal 用户，共10个用户
	 * 3）相关货币：所有货币对
	 * 4）生成策略：
	 *      a) 每个用户3种类型操作各1个。即，只存款，只取款，存款+换汇，换汇+取款，存款+换汇+取款
	 *      b) 金额范围 [200, 1000]
	 *
	 * 相关表: transactions, user_logs
	 * 
	 * @param  $user - User 对象 
	 * @return void
	 */	
	private function _createTransactions($user)
	{
		return array();
	}

	/**
	 * 
	 * 4. 生成收款人 (Recipient)，生成策略：
	 * 1）相关用户：demo
	 * 2）生成策略：随机生成60个recipients
	 *
	 * 相关表: recipients, user_logs
	 * 
	 * @param  $user - User 对象 
	 * @return void
	 */	
	private function _createRecipients($uids)
	{

		$_created_at = Carbon::now()->toDateTimeString();

		foreach($uids as $_uid)
		{
			for ($_idx = 0; $_idx < DemoSimulate::$_create_recipient_cnt; $_idx++)
			{
				$_recipient = array();
				$_recipient['uid'] = $_uid;
				$_recipient['account_bsb'] = '012321';
				$_recipient['account_name'] = DemoSimulate::$_data_prefix . rand();
				$_recipient['country'] = 'Australia';
				$_recipient['created_at'] = $_created_at;
				$_recipient['updated_at'] = $_created_at;

				DB::table('recipients')->insert($_recipient);	

			}

		}

		return;
	}


	private function _rollback()
	{
		try 
		{

			/**
			 * all SQL must be in a transaction
			 */
			/* 
			 * begin MySQL transaction 
			 */
			$this->info('Begin Rollback...');			
			DB::connection()->getPdo()->beginTransaction();

			$_uids = DB::table('users')
					->where('first_name', 'like', DemoSimulate::$_data_prefix . '%')
					->orderBy('created_at', 'desc')
					->take(DemoSimulate::$_create_user_cnt)
					->lists('uid')
					;

			/**
			 * delete user balance
			 */
			$this->info('delete Balance...');				
			$this->_deleteBalance($_uids);
			/**
			 * delete user recipients
			 */
			$this->info('delete Recipients...');	
			$this->_deleteRecipients($_uids);

			/**
			 * delete user receipts
			 */
			$this->info('delete Receipts...');	
			$this->_deleteReceipts($_uids);

			/**
			 * delete user tx
			 */
			$this->info('delete Transactions...');	
			$this->_deleteTransactions($_uids);

			/**
			 * delete Users
			 */
			$this->info('delete Users...');			
			$this->_deleteUsers($_uids);

			/* 
			 * commit MySQL transaction
			 */
			DB::connection()->getPdo()->commit();
			$this->info('Rollback Successfully.');	

		} 
		catch (\PDOException $e)
		{
			$this->error("Simulation rolls back.");
			$this->error($e->getMessage());
			/* rollback */
			DB::connection()->getPdo()->rollBack();

		}

		return;		
	}

	private function _deleteUsers($uids)
	{

		DB::table('verifications')->whereIn('uid', $uids)->delete();
		DB::table('profiles')->whereIn('uid', $uids)->delete();
		DB::table('users')->whereIn('uid', $uids)->delete();	

		return;
	}

	private function _deleteBalance($uids)
	{

		DB::table('balances')->whereIn('uid', $uids)->delete();

		return;
	}


	private function _deleteRecipients($uids)
	{
		DB::table('recipients')->whereIn('uid', $uids)->delete();
		return;
	}

	private function _deleteReceipts($uids)
	{
		DB::table('balance_history')->whereIn('uid', $uids)->delete();
		DB::table('receipts')->whereIn('uid', $uids)->delete();
		return;
	}

	private function _deleteTransactions($uids)
	{
		DB::table('transactions')->whereIn('uid', $uids)->delete();
		return;
	}

}
