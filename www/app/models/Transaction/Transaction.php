<?php namespace Tt\Model;
use App;
use Config;

use Tx;
use DebtTx;
use Carbon;

class Transaction {

	/**
	 * 这里定义所有类型的借贷产品，所有类型的产品共用
	 */
	const PRODUCT_TYPE_B2P	= 'b2p';
	const PRODUCT_TYPE_UNI	= 'uni';
	const PRODUCT_TYPE_FRD	= 'frd';
	/**
	 * status，所有类型的产品共用
	 */
	const STATUS_FIRST_AUDITING		= 0; 	// 初始状态，刚刚提交待初审，初审中
	const STATUS_LENDING			= 1; 	// 初审通过，招标中
	const STATUS_FIRST_AUDIT_FAILED = 2;	// 初审驳回
	const STATUS_SEC_AUDITING		= 3;	// 招标成功，复审中
	const STATUS_LENGING_FAILED		= 4;	// 招标失败
	const STATUS_REPAYING			= 5;	// 复审通过，还款中
	const STATUS_SEC_AUDIT_FAILED	= 6;	// 复审驳回
	const STATUS_REPAY_COMPLETED	= 7;	// 已还完
	const STATUS_REPAY_COMPLETED_ADVANCED	= 8;	// 提前还完
	
	/**
	 * 获取每种产品的配置
	 */
	public static function config($product_type)
	{
		$_cfg	= Config::get('debt_product');
	
		return $_cfg[$product_type];
	}

	
	public static function status_arr()
	{
		/**
		 * array(id => desc)
		 */
		return array(
			Tx::STATUS_FIRST_AUDITING 	=> '初审中',
			Tx::STATUS_LENDING 			=> '招标中',
			Tx::STATUS_FIRST_AUDIT_FAILED	=>'初审驳回',			
			Tx::STATUS_SEC_AUDITING		=>'已满标',							
			Tx::STATUS_LENGING_FAILED	=>'流标',								
			Tx::STATUS_REPAYING			=>'还款中',									
			Tx::STATUS_SEC_AUDIT_FAILED	=>'复审驳回',				
			Tx::STATUS_REPAY_COMPLETED	=>'已还款',				
			Tx::STATUS_REPAY_COMPLETED_ADVANCED	=>'提前还款',
		);
	}
	
	/**
	 * 用于无法获得WithdrawTransaction对象的操作，如连表
	 */
	public static function status_desc($status)
	{
		$_status_arr = Tx::status_arr();
	
		return isset($_status_arr[$status]) ? $_status_arr[$status] : '';
	}
	/**
	 * bootstrap label
	 * 	http://v3.bootcss.com/components/#labels
	 *
	 */
	public static function status_style($status)
	{
		switch ($status)
		{
			case Tx::STATUS_FIRST_AUDITING:
			case Tx::STATUS_SEC_AUDITING:
				return 'success';
				 
			case Tx::STATUS_FIRST_AUDIT_FAILED:
			case Tx::STATUS_LENGING_FAILED:
			case Tx::STATUS_SEC_AUDIT_FAILED:
				return 'grey';
				 
			case Tx::STATUS_LENDING:
				return  'primary';
			
			case Tx::STATUS_REPAYING:
				return  'info';
				
			case Tx::STATUS_REPAY_COMPLETED:
			case Tx::STATUS_REPAY_COMPLETED_ADVANCED:
				return 'default';
				 
			default:
				return 'grey';
		}
	}

	public static function detail_url($receipt_number, $id, $user_type = 'client')
	{
		$_tag	= substr($receipt_number, 0 ,1);
		
		switch ($_tag)
		{
			case 'P':	// b2p
				return $user_type == 'admin' ? 
					url('/admin/debt/pawn-detail/'.$id) : 
					url('/invest/pawn-detail/'.$id);
				
			case 'U':	// uni
				return $user_type == 'admin' ? 
					url('/admin/debt/uni-detail/'.$id) : 
					url('/invest/uni-detail/'.$id);
				
			case 'F':	// friend
				return $user_type == 'admin' ? 
					url('/admin/debt/friend-detail/'.$id) : 
					url('/invest/friend-detail/'.$id);
		}
		
		return '#';
	}

	public static function type_desc($receipt_number)
	{
		$_tag	= substr($receipt_number, 0 ,1);
		$_cfg	= Config::get('debt_product');
		
		switch ($_tag)
		{
			case 'P':	// b2p
			{
				return $_cfg[Tx::PRODUCT_TYPE_B2P]['title'];
			}
		
			case 'U':	// uni
				return $_cfg[Tx::PRODUCT_TYPE_UNI]['title'];
		
			case 'F':	// friend
				return $_cfg[Tx::PRODUCT_TYPE_FRD]['title'];
		}
		
		return '';		
	}
	
	public static function mydebt_txs($date_start, $date_end = '', $search = '', $status = '', $page_record_cnt = 0, $limit = 0)
	{
		$_ret = array();
		$_user	= login_user();
		
		$_debt_txs	= DebtTx::where('created_at', '>=', $date_start)
								->where('uid', $_user->uid);
		
		if (!empty($status))
		{
			$_debt_txs->where('status', $status);
		}
		if (!empty($date_end))
		{
			$_debt_txs->where('created_at', '<=', $date_end . ' 23:59:59'); // 包含当天
		}
		if (!empty($search))
		{
			$_debt_txs->whereRaw("title like ?", array($search."%"));
		}
		if ($page_record_cnt != 0)
		{
			return $_debt_txs->paginate($page_record_cnt);
		}

		if ($limit == 0)
		{
			return $_debt_txs->get();	
		}	
		
		return $_debt_txs->take($limit)->get();
	}

	public static function debtlist_txs($page_record_cnt = 0)
	{
		$_ret = array();
		$_user	= login_user();
		
		$_start_date = Carbon::now()->subYears(4)->toDateString();
		
		$_debt_txs	= DebtTx::join('users', 'users.uid', '=', 'debt_transaction.uid')
		->where('debt_transaction.created_at', '>=', $_start_date)
		->whereIn('debt_transaction.status', array(Tx::STATUS_LENDING,
				Tx::STATUS_SEC_AUDITING,
				Tx::STATUS_REPAYING,
				Tx::STATUS_REPAY_COMPLETED,
				Tx::STATUS_REPAY_COMPLETED_ADVANCED))
		->select('users.credit_level', 'debt_transaction.*');
	
		if ($page_record_cnt != 0)
		{
			$_debt_txs = $_debt_txs->paginate($page_record_cnt);
		}
		else
		{
			$_debt_txs = $_debt_txs->get();
		}
	
		return $_debt_txs;
	}
}