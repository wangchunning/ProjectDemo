<?php namespace Controllers\Admin;

use Log;
use DB;
use App;
use View;
use Validator;
use Input;
use Auth;
use Request;
use Redirect;
use Controllers\AdminController;

use User;
use Balance;
use BalanceHistory;
use WithdrawTx;
use Administrator;
use AdminActivityLog;
/**
 *  财务管理
 *
 */
class FinanceController extends AdminController {


	public function getIndex()
	{
		return Redirect::to('/admin/finance/withdraw');
	}
	
	public function getWithdraw()
	{
		check_perm('view_withdraw');
		
		$_txs	= WithdrawTx::join('users', 'users.uid', '=', 'withdraw_transaction.uid');
		
		$_status		= '';
		$_start_date	= '';
		$_end_date		= '';
		$_search		= '';
		
		if (Input::has('status'))
		{
			$_status = Input::get('status');
			$_txs->where('withdraw_transaction.status', $_status);
		}
		if (Input::has('start_date'))
		{
			$_start_date = Input::get('start_date');
			$_txs->where('withdraw_transaction.created_at', '>=', $_start_date);
		}		
		if (Input::has('end_date'))
		{
			$_end_date = Input::get('end_date');
			$_txs->where('withdraw_transaction.created_at', '<=', $_end_date);
		}
		if (Input::has('search'))
		{
			$_search = Input::get('search');
			$_txs->where('users.user_name', $_search);
			//whereRaw("concat(users.first_name, ' ',users.last_name) like ?", array("%".$search."%"))
		}
		$_txs->select('users.user_name', 'users.email', 'users.mobile', 'withdraw_transaction.*');
		$_user_txs = $_txs->paginate($this->DEFAULT_PAGINATION_CNT);
		
		$this->data['status'] 		= $_status;
		$this->data['status_arr'] 	= array_merge(array('' => '全部状态'), WithdrawTx::status_arr());
		$this->data['start_date'] 	= $_start_date;
		$this->data['end_date'] 	= $_end_date;
		$this->data['search'] 		= $_search;
		$this->data['user_txs'] 	= $_user_txs;
		
		$this->layout->content = View::make('admin.finance.withdraw.index', $this->data);
		
	}
	
 	public function getWithdrawDetail($tx_id)
 	{
 		
 		$_user_tx	= WithdrawTx::join('users', 'users.uid', '=', 'withdraw_transaction.uid')
 						->where('withdraw_transaction.id', $tx_id)
 						->select('users.user_name', 'users.email', 'users.mobile', 'withdraw_transaction.*')
 						->firstOrFail();
 		
 		$this->data['user_tx'] 	= $_user_tx;
 		
 		$this->layout->content = View::make('admin.finance.withdraw.tx_detail', $this->data);
 	}
 	
 	public function postWithdrawClear($tx_id)
 	{
 		check_perm('audit_withdraw');
 		
 		$_tx 		= WithdrawTx::findOrFail($tx_id);
 		$_user		= User::findOrFail($_tx->uid);
 		$_admin		= login_user(Administrator::LABEL);
 		
 		try
 		{
 			DB::beginTransaction();
 			$_balance	= Balance::findOrFail($_tx->uid);
 			if ($_balance->total_amount < $_tx->total_amount)
 			{
 				App::abort(404);
 			}
 			/**
 			 * 更新tx状态
 			 */
 			$_tx->status = WithdrawTx::STATUS_CONFIRMED;
 			$_tx->save();
 			/**
 			 * 修改用户余额，可用余额不变，总余额减少
 			 */
 			$_balance->total_amount -= $_tx->total_amount;
 			$_balance->save();
 			/**
 			 * 写入管理员操作日志
 			 */
 			$_log = new AdminActivityLog;
 			$_log->uid			= $_admin->uid;
 			$_log->user_name	= $_admin->user_name;
 			$_log->op_category	= AdminActivityLog::OP_CATEGORY_FINANCE;
 			$_log->op_type		= AdminActivityLog::OP_TYPE_WITHDRAW_TX;
 			$_log->operation	= AdminActivityLog::OPERATION_WITHDRAW_TX_CLEAR;
 			$_log->obj_id		= $tx_id;
 			$_log->desc			= AdminActivityLog::make_desc($_log->operation, 
 																array('user_name' => $_user->user_name, 
 																'available_amount' => $_tx->available_amount));
 			$_log->save();
 			
 			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
			
			return Redirect::back()->with('error', '抱歉，系统暂时无法处理您的操作，请稍后再试');
		}
 		
 		return Redirect::to('/admin/finance/withdraw-detail/'.$tx_id);
 	}
 	
 	/**
 	 * cancel withdraw tx
 	 * 
 	 */
 	public function postWithdrawCancel($tx_id)
 	{
 		check_perm('audit_withdraw');
 		
 		$_tx 		= WithdrawTx::findOrFail($tx_id);
 		$_user		= User::findOrFail($_tx->uid);
 		$_admin		= login_user(Administrator::LABEL);
 		
 		try
 		{
 			DB::beginTransaction();
 			$_balance	= Balance::findOrFail($_tx->uid);

 			/**
 			 * 更新tx状态
 			 */
 			$_tx->status = WithdrawTx::STATUS_CANCELLED;
 			$_tx->save();
 			/**
 			 * 修改用户余额，总余额只扣除手续费，可用余额增加，但手续费不退
 			 * 
 			 */
 			$_balance->total_amount 	-= $_tx->fee_amount;
 			$_balance->available_amount += $_tx->available_amount;
 			$_balance->save();
 			/**
 			 * 更新余额流水
 			 */
 			$_b_log						= new BalanceHistory;
 			$_b_log->uid				= $_tx->uid;
 			$_b_log->type				= BalanceHistory::TYPE_WITHDRAW_CANCEL_REFUND;
 			$_b_log->credit_amount		= $_tx->available_amount;
 			$_b_log->available_balance	= $_balance->available_amount;
 			$_b_log->desc	= '';
 			$_b_log->save();
 			/**
 			 * 写入管理员操作日志
 			 */
 			$_log 				= new AdminActivityLog;
 			$_log->uid			= $_admin->uid;
 			$_log->user_name	= $_admin->user_name;
 			$_log->op_category	= AdminActivityLog::OP_CATEGORY_FINANCE;
 			$_log->op_type		= AdminActivityLog::OP_TYPE_WITHDRAW_TX;
 			$_log->operation	= AdminActivityLog::OPERATION_WITHDRAW_TX_CANCEL;
 			$_log->obj_id		= $tx_id;
 			$_log->desc			= AdminActivityLog::make_desc($_log->operation, 
 																array('user_name' => $_user->user_name, 
 																'available_amount' => $_tx->available_amount));
 			$_log->save();
 			
 			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
			
			return Redirect::back()->with('error', '抱歉，系统暂时无法处理您的操作，请稍后再试');
		}
 		
 		return Redirect::to('/admin/finance/withdraw-detail/'.$tx_id);
 	}
}
