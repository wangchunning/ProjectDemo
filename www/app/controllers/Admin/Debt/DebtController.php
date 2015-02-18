<?php namespace Controllers\Admin;
use DB;
use View;
use Validator;
use Input;
use Auth;
use User;
use Role;
use Request;
use Redirect;
use Controllers\AdminController;
use Carbon;

use Administrator;
use AdminActivityLog;

use UserProfile;
use Tx;
use DebtTx;
use DebtTxProfile;
use DebtTxMaterial;
use Material;

/**
 * 
 * @author Wang Chunning
 *
 */
class DebtController extends AdminController {

	public function getIndex()
	{
		check_perm('view_debt');
	
        $_start_date	= Input::get('start_date', '');
        $_end_date		= Input::get('end_date', '');
        $_search		= Input::get('search', '');
        $_status		= Input::get('status', '');
        
        if (empty($_start_date))
        {
        	$_start_date = Carbon::now()->subYears(2)->toDateString();
        }
        
        $_txs = Tx::mydebt_txs($_start_date, $_end_date, $_search, $_status, $this->DEFAULT_PAGINATION_CNT);
		
		$this->data['status'] 		= $_status;
		$this->data['status_arr'] 	= array_merge(array('' => '全部状态'), Tx::status_arr());
		$this->data['start_date'] 	= $_start_date;
		$this->data['end_date'] 	= $_end_date;
		$this->data['search'] 		= $_search;
		$this->data['txs'] 			= $_txs;
		$this->data['search_placeholder'] 	= '标题';
		
		$this->layout->content = View::make('admin.debt.index', $this->data);
	
	}
	
	public function getPawnDetail($tx_id)
	{
		check_perm('view_debt');
		
		$_user_tx	= DebtTx::join('users', 'users.uid', '=', 'debt_transaction.uid')
		->where('debt_transaction.id', $tx_id)
		->select('users.email', 'users.mobile', 'debt_transaction.*')
		->firstOrFail();
			
		$this->data['tx_config']	= Tx::config(Tx::PRODUCT_TYPE_B2P);
		$this->data['user_tx'] 		= $_user_tx;
		$this->data['profile'] 		= DebtTxProfile::findOrFail($tx_id);
		$this->data['materials'] 	= DebtTxMaterial::where('tx_id', $tx_id)->get();
			
		$this->layout->content = View::make('admin.debt.pawn.tx_detail', $this->data);
	}
	
	public function getPawnDetailEdit($tx_id)
	{
		check_perm('write_debt');
			
		$this->data['tx_config']	= Tx::config(Tx::PRODUCT_TYPE_B2P);
		$this->data['tx'] 			= DebtTx::findOrFail($tx_id);
		$this->data['profile'] 		= DebtTxProfile::findOrFail($tx_id);
		$this->data['materials'] 	= DebtTxMaterial::where('tx_id', $tx_id)->get();
			
		$this->layout->content = View::make('admin.debt.pawn.tx_detail_edit', $this->data);
	}
		
	
	public function postPawnDetailEdit($tx_id)
	{
		//Input::merge(array_map('trim', Input::all()));
		 
		$_validator = Validator::make(Input::all(), array(
				'company_name'		=> 'required',
				'industry'			=> 'required',
				'purpose'			=> 'required',
				'repayment_from'	=> 'required',
				'state'				=> 'required',
				'city'				=> 'required',
				'register_capital'  => 'required|money_amount',
				'property_value'    => 'required|money_amount',
				'last_year_income'  => 'required|money_amount',
				'pawn'      		=> 'required',
				'register_date'     => 'required|date',
				'desc'				=> 'required',
		));
		
		if ($_validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($_validator);
		}
		
		$_tx_profile	= DebtTxProfile::findOrFail($tx_id);
		
		$_tx_profile->company_name		= Input::get('company_name');
		$_tx_profile->industry 			= UserProfile::company_category_desc(Input::get('industry'));
		$_tx_profile->purpose 			= Input::get('purpose');
		$_tx_profile->repayment_from 	= Input::get('repayment_from');
		$_tx_profile->state 			= Input::get('state');
		$_tx_profile->city 				= Input::get('city');
		$_tx_profile->register_capital 	= Input::get('register_capital');
		$_tx_profile->property_value 	= Input::get('property_value');
		$_tx_profile->last_year_income 	= Input::get('last_year_income');
		$_tx_profile->pawn 				= Input::get('pawn');
		$_tx_profile->register_date 	= Input::get('register_date');
		$_tx_profile->desc				= \Illuminate\Support\Facades\Input::get('desc');
		$_tx_profile->save();
		
		return Redirect::to('/admin/debt/pawn-detail/'.$tx_id);
	}
	
	
	public function getDelMaterial($id)
	{
		check_perm('write_debt');
		
		$_material = DebtTxMaterial::findOrFail($id);
		$_material->delete();
		
		return Redirect::back()->with('message', $_material->type . '已删除');
		
	}
	
	
	public function getPawnMaterialAdd($tx_id)
	{
		check_perm('write_debt');
		
		$this->data['tx_id']	= $tx_id;
		 
		$this->layout->content 	= View::make('admin.debt.pawn.tx_detail_material_add', $this->data);
	}
	
	
	public function postPawnMaterialAdd($tx_id)
	{
		check_perm('write_debt');
		
		$_messages = array(
				'm_files.required'	=> '请上传认证材料',
		);
		$_validator = Validator::make(Input::all(), array(
				'm_files'     => 'required',
		), $_messages);
		
		if ($_validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($_validator);
		}
		
		$_admin = login_user(Administrator::LABEL);
		foreach (Input::get('m_files') as $_file_path)
		{
			$_material 				= new DebtTxMaterial;
			$_material->tx_id 		= $tx_id;
			$_material->op_uid		= $_admin->uid;
			$_material->op_user_name= $_admin->user_name;
			$_material->type		= Input::get('m_type');
			$_material->name		= basename($_file_path); // 文件名
			$_material->path		= $_file_path;
			$_material->desc		= '';
			
			$_material->save();
		}
		
		return Redirect::to('/admin/debt/pawn-detail/'.$tx_id)->with('message', Input::get('m_type').'已添加');
	}
	
	
	public function postPawnFirstAuditSucc($tx_id)
	{
		check_perm('write_debt');

		$_tx 		= DebtTx::findOrFail($tx_id);
		$_user		= User::findOrFail($_tx->uid);
		$_admin		= login_user(Administrator::LABEL);
			
		try
		{
			DB::beginTransaction();

			/**
			 * 更新tx状态
			 */
			$_tx->status = Tx::STATUS_LENDING;
			$_tx->save();

			/**
			 * 写入管理员操作日志
			*/
			$_log = new AdminActivityLog;
			$_log->uid			= $_admin->uid;
			$_log->user_name	= $_admin->user_name;
			$_log->op_category	= AdminActivityLog::OP_CATEGORY_BORROW;
			$_log->op_type		= AdminActivityLog::OP_TYPE_DEBT_TX;
			$_log->operation	= AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_SUCC;
			$_log->obj_id		= $tx_id;
			$_log->desc			= AdminActivityLog::make_desc($_log->operation,
					array('user_name' => $_user->user_name,
							'title' => $_tx->title));
			$_log->save();
	
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
				
			return Redirect::back()->with('error', '抱歉，系统暂时无法处理您的操作，请稍后再试');
		}
			
		return Redirect::to('/admin/debt/pawn-detail/'.$tx_id);
	}
	
	public function postPawnFirstAuditFail($tx_id)
	{
		check_perm('write_debt');
	
		$_tx 		= DebtTx::findOrFail($tx_id);
		$_user		= User::findOrFail($_tx->uid);
		$_admin		= login_user(Administrator::LABEL);
			
		try
		{
			DB::beginTransaction();
	
			/**
			 * 更新tx状态
			*/
			$_tx->status = Tx::STATUS_FIRST_AUDIT_FAILED;
			$_tx->save();
	
			/**
			 * 写入管理员操作日志
			*/
			$_log = new AdminActivityLog;
			$_log->uid			= $_admin->uid;
			$_log->user_name	= $_admin->user_name;
			$_log->op_category	= AdminActivityLog::OP_CATEGORY_BORROW;
			$_log->op_type		= AdminActivityLog::OP_TYPE_DEBT_TX;
			$_log->operation	= AdminActivityLog::OPERATION_DEBT_TX_FIRST_AUDIT_FAIL;
			$_log->obj_id		= $tx_id;
			$_log->desc			= AdminActivityLog::make_desc($_log->operation,
					array('user_name' => $_user->user_name,
							'title' => $_tx->title));
			$_log->save();
	
			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
	
			return Redirect::back()->with('error', '抱歉，系统暂时无法处理您的操作，请稍后再试');
		}
			
		return Redirect::to('/admin/debt/pawn-detail/'.$tx_id);
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
