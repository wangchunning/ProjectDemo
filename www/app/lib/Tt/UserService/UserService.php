<?php namespace Tt\UserService;

use Auth;
use Request;
use Session;
use Hash;
use Input;
use DB;
use ID5;
use Log;
use Mail;
use Ret;
use Carbon;

use User;
use UserProfile;
use BalanceHistory;
use DepositTx;
use Balance;
use UserBankAccount;
use WithdrawTx;
use DebtTx;
use DebtInvestDetail;
use Repayment;
use DebtInvest;
/**
 */

class UserService {

    /**
     * 
     * 
     * @return 成功： session_id; 失败：0
     * 
     */
    public function create_user_and_login($input_arr, $login_from)
    {	
    	
    	$_user	= new User;
    	
    	try
    	{
    		DB::beginTransaction();

	    	$_user->email      		= Input::get('email');
	    	$_user->login_password	= Hash::make(Input::get('password'));
	    	//$_user->mobile			= Input::get('mobile');
	    	$_user->user_name  		= Input::get('user_name');
	    	$_user->register_ip		= Request::getClientIp();
	    	$_user->register_ip_area= ip_state($_user->register_ip);
	    	
	    	$_user->save();
	    	
	    	DB::commit();
    	}
    	catch (\PDOException $e)
    	{
    		DB::rollBack();
    		return Ret::RET_FAILED;
    	}
    	// 自动登录
    	Auth::loginUsingId($_user->uid);

    	$_user->save_access_log($login_from);

    	return Session::getId();
    	
    }

    public function id_verify($real_name, $id_number)
    {
    	$_user		= login_user();
    	$_profile	= new UserProfile;
    	
    	
    	if (ID5::validate($real_name, $id_number) === false)
    	{
    		return false;
    	}
    	
    	try
    	{
    		DB::beginTransaction();
    	
    		$_profile->uid      	= $_user->uid;
    		$_profile->real_name	= $real_name;
    		$_profile->id_number	= $id_number;
    		$_profile->set_attr_from_id_number();
    		$_profile->save();
    		
    		$_user->is_id_verified 	= 1; 
    		$_user->save();
    		
    		DB::commit();
    	}
    	catch (\PDOException $e)
    	{
    		Log::error($e);
    		DB::rollBack();
	
    		return Ret::RET_FAILED;
    	}

    	return Ret::RET_SUCC;
    }
    
	public function send_verify_email($new_email)
	{
		$_user = login_user();
		
		if ($_user->email == $new_email &&
				$_user->is_email_verified)
		{
			return Ret::RET_EMAIL_DUPLICATED_VERIFY;
		}
		
		try
		{
			DB::beginTransaction();
			
			if ($_user->email != $new_email)
			{
				$_user->email = $new_email;
				$_user->is_email_verified = 0;
				$_user->save();
			}
		
			// 邮件验证 token
			$_token = str_random(20);
			
			$_verify 		= $_user->verify();
			$_verify->uid 	= $_user->uid;		
			$_verify->email_verify_token = Hash::make($_token);	
			$_verify->save();
			
			DB::commit();
		}
		catch (\PDOException $e)
		{
			DB::rollBack();
			return Ret::RET_FAILED;
		}
		
		$_data = array(
			'user' => $_user->user_name,
			'email' => $new_email,
			'url' => url(sprintf("activate/confirm/%s/%s", $_user->uid, $_token))
		);
		
		Mail::send('emails.welcome', $_data, function($message) use ($new_email)
		{
			$message->to($new_email)->subject('验证邮件 - 天添财富');
		});
		
		return Ret::RET_SUCC;
	}

	/**
	 * 存款
	 * 
	 * @param $amount 		用户实际支付的钱数，real_amount + fee_amount
	 * @param $real_amount 	实际到账金额
	 * @param $fee_amount	手续费
	 * 
	 * @return 0/1
	 */
	public function add_fund($amount, $real_amount, $fee_amount)
	{

		$_user = login_user();

		try
		{
			DB::beginTransaction();
			
			$_balance	= $_user->balance();
			if (empty($_balance))
			{
				$_balance = new Balance;
			}
			
			$_log						= new BalanceHistory;
			$_log->uid					= $_user->uid;
			$_log->type					= BalanceHistory::TYPE_FUND_AMOUNT;
			$_log->credit_amount		= $amount;
			$_log->available_balance	= $_balance->available_amount + $amount;
			$_log->desc	= '';
			$_log->save();
			
			$_log						= new BalanceHistory;
			$_log->uid					= $_user->uid;
			$_log->type					= BalanceHistory::TYPE_FUND_FEE;
			$_log->debt_amount			= $fee_amount;
			$_log->available_balance	= $_balance->available_amount + $real_amount;
			$_log->desc	= '';				
			$_log->save();
		
			$_balance->uid				= $_user->uid;
			$_balance->available_amount	+= $real_amount;
			$_balance->total_amount		+= $real_amount;
			$_balance->save();
			
			$_tx					= new DepositTx;
			$_tx->uid				= $_user->uid;
			$_tx->type				= DepositTx::TYPE_AUTO;
			$_tx->receipt_number	= tn('D');
			$_tx->deposit_amount	= $amount;
			$_tx->available_amount	= $real_amount;
			$_tx->fee_amount		= $fee_amount;
			$_tx->save();
			
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
		
			return Ret::RET_FAILED;
		}
		
		return Ret::RET_SUCC;		
	}
	
	/**
	 * 取款
	 * 
	 * @param $total_amount 用户实际支付的钱数，real_amount + fee_amount
	 * @param $real_amount 	实际到账金额
	 * @param $fee_amount	手续费
	 * 
	 * @return 0/1
	 */
	public function add_withdraw($total_amount, $real_amount, $fee_amount,
									$bank_name, $branch_name, $account_name, $account_number)
	{
	
		$_user = login_user();
	
		try
		{
			DB::beginTransaction();
				
			$_balance	= $_user->balance();
			/**
			 * 余额不足
			 */
			if (empty($_balance) ||
					$_balance->available_amount < $total_amount)
			{
				return Ret::RET_WITHDRAW_BALANCE_NOT_ENOUGH;
			}
			
			/**
			 * 更新流水
			 */
			$_log						= new BalanceHistory;
			$_log->uid					= $_user->uid;
			$_log->type					= BalanceHistory::TYPE_WITHDRAW_AMOUNT;
			$_log->debt_amount			= $real_amount;
			$_log->available_balance	= $_balance->available_amount - $real_amount;
			$_log->desc	= '';
			$_log->save();
				
			$_log						= new BalanceHistory;
			$_log->uid					= $_user->uid;
			$_log->type					= BalanceHistory::TYPE_WITHDRAW_FEE;
			$_log->debt_amount			= $fee_amount;
			$_log->available_balance	= $_balance->available_amount - $total_amount;
			$_log->desc	= '';
			$_log->save();
			/**
			 * 更新可用余额，总余额不变
			 */	
			$_balance->uid				= $_user->uid;
			$_balance->available_amount	-= $total_amount;
			$_balance->save();
			/**
			 * 写入tx
			 */
			$_tx					= new WithdrawTx;
			$_tx->uid				= $_user->uid;
			$_tx->receipt_number	= tn('W');
			$_tx->bank_name			= $bank_name;
			$_tx->bank_full_name	= $branch_name;
			$_tx->account_name		= $account_name;
			$_tx->account_number	= $account_number;
			$_tx->total_amount		= $total_amount;
			$_tx->available_amount	= $real_amount;
			$_tx->fee_amount		= $fee_amount;
			$_tx->save();
			/**
			 * 更新银行卡信息
			 */			
			$_bank = $_user->bank_account();
			if (empty($_bank))
			{
				$_bank = new UserBankAccount;
			}
			// 是否需要更新
			if ($_bank->account_number != $account_number)
			{
				$_bank->uid 			= $_user->uid;
				$_bank->bank_name		= $bank_name;
				$_bank->bank_full_name	= $branch_name;
				$_bank->account_name	= $account_name;
				$_bank->account_number	= $account_number;	
				$_bank->save();
			}
			
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
	
			return Ret::RET_FAILED;
		}
	
		/**
		 * 发短息
		 */
		$_text = sprintf('您从天添财富账户提现%s。收款账号：%s **** %s。如果不是您本人操作，请及时联系我们',
				currencyFormat($real_amount),
				substr($account_number, 0, 4),
				substr($account_number, -4));
		 
		//send_sms($_user->mobile, $_text);
		
		return Ret::RET_SUCC;
	}
	
	
	public function save_bank_account($bank_name, $branch_name, $account_number)
	{
		
		$_user 			= login_user();
		$_profile		= $_user->profile();
		
		$_bank_account 	= $_user->bank_account();
		if (empty($_bank_account))
		{
			$_bank_account = new UserBankAccount;
		}
						
		try
		{
			DB::beginTransaction();
			
			$_bank_account->uid				= $_user->uid;
			$_bank_account->account_name	= $_profile->real_name;
			$_bank_account->account_number	= $account_number;
			$_bank_account->bank_name		= $bank_name;
			$_bank_account->bank_full_name	= $branch_name;
			$_bank_account->save();
			
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
			
			return Ret::RET_FAILED;
		}
		
		return Ret::RET_SUCC;
	}

	/**
	 * 提交抵押融资申请
	 *
	 * @param DebtTransaction
	 * 
	 * @return 0/1/id
	 */
	public function add_debt($debt_transaction)
	{
		$_tx	= $debt_transaction;
		$_user 	= login_user();
	
		try
		{
			DB::beginTransaction();
				
			/**
			 * 写入tx
			 */
			$_tx->uid				= $_user->uid;
			$_tx->user_name			= $_user->user_name;
			$_tx->receipt_number	= tn('P');
			$_tx->save();
			/**
			 * tx_profile
			 */
			$_tx_profile		= $_tx->tx_profile;
			$_tx_profile->tx_id	= $_tx->id;
			$_tx_profile->save();
			/**
			 * tx_material
			 */			
			foreach($_tx->tx_materials as $_material)
			{
				$_material->tx_id	= $_tx->id;
				$_material->desc	= '';
				$_material->save();
			}
			
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
	
			return Ret::RET_FAILED;
		}
	
		/**
		 * 发短息
		 */
		$_text = sprintf('您的借款申请已经提交成功，我们会尽快完成审核，如果有需要我们会与您取得联系，感谢合作');
			
		//send_sms($_user->mobile, $_text);
	
		return $_tx->id;
	}

	
	/**
	 * 投资抵押融资
	 *
	 * @param DebtInvest
	 *
	 * @return 0/1/id
	 */
	public function add_debt_invest($debt_invest)
	{
		$_invest	= $debt_invest;
		$_user 		= login_user();
		$_tx 		= DebtTx::findOrFail($_invest->tx_id);
		$_benefit	= Repayment::calc_benefit($_invest->invest_amount, $_invest->tx_period, $_tx->rate);
		
		try
		{
			DB::beginTransaction();
			$_balance	= $_user->balance();
			/**
			 * 余额不足
			*/
			if (empty($_balance) ||
					$_balance->available_amount < $_invest->invest_amount)
			{
				return Ret::RET_INVEST_BALANCE_NOT_ENOUGH;
			}
				
			/**
			 * 更新流水
			 */
			$_log						= new BalanceHistory;
			$_log->uid					= $_user->uid;
			$_log->type					= BalanceHistory::TYPE_INVEST_AMOUNT;
			$_log->debt_amount			= $_invest->invest_amount;
			$_log->available_balance	= $_balance->available_amount - $_invest->invest_amount;
			$_log->desc	= sprintf('投资“%s”', $_tx->title);
			$_log->save();
			
			/**
			 * 更新可用余额，总余额不变
			*/
			$_balance->available_amount	-= $_invest->invest_amount;
			$_balance->save();
			
			$_tx = DebtTx::findOrFail($_invest->tx_id);
			if ($_tx->total_amount - $_tx->invest_amount < $_invest->invest_amount)
			{
				return Ret::RET_FAILED;
			}
			
			$_tx->invest_amount += $_invest->invest_amount;
			if ($_tx->invest_amount == $_tx->total_amount)
			{
				$_tx->status = Tx::STATUS_SEC_AUDITING;
			}
			$_tx->save();
			/**
			 * 写入DebtInvest
			*/
			$_invest->receipt_number	= tn('I');
			$_invest->save();
			/**
			 * debt_invest_detail
			*/
			foreach ($_benefit['detail'] as $_d)
			{
				$_detail					= new DebtInvestDetail;
				$_detail->tx_id				= $_invest->tx_id;
				$_detail->investor_uid		= $_invest->investor_uid;
				$_detail->debt_investor_id	= $_invest->id;
				$_detail->period_cnt		= $_d['period_cnt'];
				$_detail->total_amount		= $_d['total_amount'];
				$_detail->principal_amount	= $_d['principal_amount'];
				$_detail->interest_amount	= $_d['interest_amount'];
				$_detail->unpaid_total_amount= $_d['unpaid_total_amount'];
				
				$_detail->save();				
			}

				
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
	
			return Ret::RET_FAILED;
		}
	
		/**
		 * 发短息
		 */
		$_text = sprintf('您已成功投资%s，如果流标，您的投资会被退回，请您及时关注标的状态变化', $_invest->invest_amount);
			
		//send_sms($_user->mobile, $_text);
	
		return Ret::RET_SUCC;
	}

	
	public function my_invests($page_record_cnt = 0, $limit = 0)
	{
		$_user	= login_user();
		$_start_date = Carbon::now()->subYears(4)->toDateString();
		
		$_invests = DebtInvest::join('debt_transaction', 'debt_investor.tx_id', '=', 'debt_transaction.id')
							->where('investor_uid', $_user->uid)
							->where('debt_investor.created_at', '>', $_start_date)
							->select('debt_investor.*', 'debt_transaction.status')
							->orderBy('debt_investor.created_at', 'desc');
		
		if ($page_record_cnt != 0)
		{
			return $_invests->paginate($page_record_cnt);
		}
							
		if ($limit != 0)
		{
			$_invests = $_invests->take($limit);
		}
		
		return $_invests->get();
	}
	
}