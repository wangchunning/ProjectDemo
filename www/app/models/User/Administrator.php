<?php namespace Model;

use Carbon;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;
use Session;
use Log;
use DB;
use Request;
use App;
use Ip2loc;
use AdminAccessLog;

use LoginService;

class Administrator extends \Eloquent implements UserInterface, RemindableInterface {

	use HasRole;

	const LABEL = 'admin';
	
	const TYPE_VALUE_NORMAL 	= 0;
	const TYPE_VALUE_AGENT 		= 1;
	const TYPE_VALUE_BUSINESS 	= 2;
	const TYPE_VALUE_ADMIN		= 3;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'administrators';

    /**
     * The PK used by the model.
     *
     * @var string
     */
	protected $primaryKey = 'uid';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * 没有使用 自动登录 功能
	 */
	public function getRememberToken()
	{
		return null;
	    //return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		return ;
	    //$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		/**
		 * 因为不使用 “记住我” 功能，所以这里设置 为 null
		 * 否则这里应该返回 User表对应的 remember_token列
		 */
	    return null;
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->uid;
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * 用户登录成功后记录日志  adminstrator_log 表
	 */
	public function save_access_log($login_from)
	{
		
		$_login_ip 		= Request::getClientIp();
		$_login_area  	= ip_state($_login_ip);
		
		try
		{
			DB::beginTransaction();
			/**
			 * write log
			 */			
			$_log = new AdminAccessLog;
			$_log->uid 			= $this->uid;
			$_log->user_name 	= $this->user_name;
			$_log->login_ip 	= $_login_ip;
			$_log->login_ip_area= $_login_area;
			$_log->login_at 	= Carbon::now()->toDateTimeString();
			
			$_log->save();

			/**
			 * update admin session
			 */
			$this->login_session = Session::getId();
			$this->save();

			DB::commit();
		}
		catch (\PDOException $e)
		{
			Log::error($e);
			DB::rollBack();
		}
		
	}
	
	
	/**
	 * 用户所属角色拼接
	 *
	 *
	 * @return string
	 */
	public function role_name()
	{
		$role_name = array();
		
		foreach ($this->roles as $k => $role)
		{
			$role_name[] = $role->name;
		}
		
		return implode(',', $role_name);
	}
	
	
	
	
	
	
	
	
	//--------------------------------
	
	/**
	 * 获取用户验证信息
	 *
	 * @return WeXchange\Model\Verification
	 */
	public function verify()
	{
		return $this->hasOne('Verification', 'uid');
	}

	/**
	 * 获取用户企业信息
	 *
	 * @return \Model\UserBusiness
	 */
	public function business()
	{
		return UserBusiness::find($this->uid);
	}

	public function business_financing($status = '')
	{
		$_obj = BusinessFinance::where('apply_uid', $this->uid);

		if (!empty($status))
		{
			$_obj->where('status', $status);
		}

		return $_obj->get();
	}

	/**
	 * 获取用户配置信息
	 *
	 * 
	 * @return WeXchange\Model\Profile
	 */
	public function profile()
	{
		return $this->hasOne('Profile', 'uid');
	}

	/**
	 *  获取用户的地址薄（收款人）
	 *
	 * @return WeXchange\Model\Recipient
	 */
	public function recipients()
	{
		return $this->hasMany('Recipient', 'uid');
	}

	/**
	 * 获取该用户的收款人银行账号
	 *
	 * @return Object
	 */
	public function recipientBankAccounts()
	{
		return DB::table('users')
				->join('recipients', 'users.uid', '=', 'recipients.uid')
				->join('recipient_banks', 'recipient_banks.recipient_id', '=', 'recipients.id')
				->where('users.uid', '=', $this->uid)
				->select(
					'recipient_banks.*',
					'recipients.account_name', 
					'recipients.email', 
					'recipients.transfer_country',
					'recipients.address',
					'recipients.address_2',
					'recipients.address_3',
					'recipients.postcode',
					'recipients.phone_code',
					'recipients.phone_type',
					'recipients.phone'
				);
	}

	/**
	 * 获取用户的平台余额
	 *
	 *
	 * @return WeXchange\Model\Balance
	 */
	public function balances()
	{
		return $this->hasMany('Balance', 'uid');
	}



	/**
	 * Get children for this user
	 *
	 * @return WeXchange\Model\User
	 */
	public function children()
	{
		//return $this->hasManyThrough('User', 'UserMapping', 'parent_id', 'user_id');
		return DB::table('user_mapping')
				->join('users', 'user_mapping.user_id', '=', 'users.uid')
				->where('user_mapping.parent_id', '=', $this->uid)
				->select('users.*')
				->get();
	}

	/**
	 * Get children for this user
	 *
	 * @return WeXchange\Model\User
	 */
	public function child($email)
	{
		$children = $this->children();
		foreach ($children as $child)
		{
			if ($child->email == $email)
			{
				return $child;
			}
		}

		return;
	}

	/**
	 * Get parents for this user
	 *
	 * @return WeXchange\Model\User
	 */
	public function parent()
	{
		/** 
		 * for now, v0.9, one child must have one parent
		 */
		$user = UserMapping::join('users', 'user_mapping.parent_id', '=', 'users.uid')
				->where('user_mapping.user_id', '=', $this->uid)
				->select('users.*')
				->first();

		if (empty($user))
		{
			return $user;
		}
		
		return User::find($user->uid);
	}
	/**
	 * 获取用户的流水
	 *
	 * @return WeXchange\Model\Transaction
	 */
	public function transactions()
	{
		return $this->hasMany('Transaction', 'uid');
	}

	/**
	 * Get receipts for this user
	 *
	 * @return WeXchange\Model\Receipt
	 */
	public function receipts()
	{
		return $this->hasMany('Receipt', 'uid');
	}

	/**
	 * 用户收到的 notes
	 *
	 * @return WeXchange\Model\Notes
	 */
	public function notes()
	{
		return $this->hasMany('Notes', 'uid');
	}

	/**
	 * 管理员写的 notes
	 *
	 * @return WeXchange\Model\Notes
	 */
	public function leaveNotes()
	{
		return $this->hasMany('Notes', 'operator_id');
	}

	/**
	 *  Customers 的 activity 记录
	 *
	 * @return WeXchange\Model\Activity
	 */
	public function customerActivities()
	{
		return $this->hasMany('Activity', 'customer_id');
	}	

	/**
	 * 用户号码访问器
	 *
	 * @return string
	 */
	public function getAccountNumberAttribute()
	{
		return str_pad($this->uid, 6, '0', STR_PAD_LEFT);
	}

	/**
	 * 访问器
	 * 
	 * 用户的余额货币分别和 AUD 组成，以逗号分割
	 * 
	 * @return string
	 */
	public function getBalanceCurrencyAttribute()
	{
		$output = array();

		$balances = $this->balances()->get();

		foreach ($balances as $balance)
		{
			$output[] = $balance->currency . 'AUD';
		}

		return implode(',', $output);
	}

	/**
	 * 访问器
	 * 
	 * 用户的各货币余额转换AUD总额
	 * 
	 * @return string
	 */
	public function getBalanceToAudAttribute()
	{
		$rates = Exchange::query($this->balance_currency, $this->profile->vip_type);

		$balances = $this->balances()->get();

		return totalAUD($balances, $rates);
	}

	/**
	 * 用户状态
	 *
	 * @return string
	 */
	public function getStatusAttribute()
	{
		if ($this->deleted_at)
		{
			return 'Archived';
		}

		if ($this->profile->status == 'verified')
		{
			return 'Active';
		}

		if ($this->profile->status == 'unverified')
		{
			if ($this->last_login < Carbon::now()->subMonth())
			{
				return 'Inactive';
			}

			return 'Pending';
		}
	}

	/**
	 * 定义 query scope：筛选不同状态的用户
	 *
	 * @param  Eloquent
	 * @param  string
	 * @return WeXchange\Model\User
	 */
	public function scopeStatus($query, $status)
	{
		if ($status == 'active')
		{
			return $query->join('profiles', 'users.uid', '=', 'profiles.uid')
						 ->where('profiles.status', '=', 'verified');
		}

		if ($status == 'pending')
		{
			return $query->join('profiles', 'users.uid', '=', 'profiles.uid')
						 ->where('profiles.status', '=', 'unverified')
						 ->where('users.last_login', '>', Carbon::now()->subMonth());
		}

		if ($status == 'inactive')
		{
			return $query->join('profiles AS sp', 'users.uid', '=', 'sp.uid')
						 ->where('sp.status', '=', 'unverified')
						 ->where('users.last_login', '<', Carbon::now()->subMonth());
		}

		if ($status == 'archived')
		{
			return $query->onlyTrashed();			
		}

		return $query->where('users.type', '=', 'user');
	}

	/**
	 * 定义 query scope：不同类型的用户
	 *
	 * @param  Eloquent
	 * @return WeXchange\Model\User
	 */
	public function scopeType($query, $type)
	{
		if ($type == 'Individual')
		{
			return $query->has('business', '=', 0);			
		}

		if ($type == 'Company')
		{
			return $query->has('business');			
		}

		if (is_vip($type))
		{
			return $query->join('profiles AS pt', 'users.uid', '=', 'pt.uid')
						 ->where('pt.vip_type', '=', $type);			
		}

		return $query;
	}	

	/**
	 * 定义 query scope：通过时间筛选
	 *
	 * @param  Eloquent
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return WeXchange\Model\User
	 */
	public function scopeTimearea($query, $dw, $start_date, $expriy_date)
	{
		if ( ! $start_date AND ! $expriy_date AND $dw)
		{
			return $query->where('users.created_at', '>', word_to_date($dw));					
		}

		if (urldecode($start_date))
		{
			$query = $query->where('users.created_at', '>=', time_to_search($start_date));
		}

		if (urldecode($expriy_date))
		{
			$query = $query->where('users.created_at', '<=', time_to_search($expriy_date));
		}

		return $query;
	}
}