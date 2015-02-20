<?php namespace Model;

use Carbon;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Tt\Entrust\HasRole;
use Session;
use Request;
use DB;
use Ip2loc;
use UserAccessLog;

use LoginService;

class User extends \Eloquent implements UserInterface, RemindableInterface {

	const LABEL = 'user';	// 标识前台用户，用于区别 admin
	
	/**
	 * 封禁, 1小时允许10次错误
	 */
	const LOGIN_LIMIT_CNT 	= 10;
	const LOGIN_LIMIT_HOUR 	= 1;
	
	/**
	 * user level
	 */
	const LEVEL_CUSTOMER 	= 0;
	const LEVEL_VIP 		= 1;
	
	/**
	 * user status
	 */
	const STATUS_NORMAL 	= 1;
	const STATUS_DISABLED	= 0;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

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
	protected $hidden = array('login_password');

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
	    //$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		/**
		 * 因为不使用 “记住我” 功能，所以这里设置 为 null
		 * 否则这里应该返回 User表对应的 remember_token列
		 */
		return null;
	   // return 'remember_token';
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
		return $this->login_password;
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
	 * 
	 * @param login_from 	- 参见 LoginService
	 */
	public function save_access_log($login_from)
	{

		$_login_ip 		= Request::getClientIp();
		$_login_area  	= ip_state($_login_ip);
	
		try
		{
			DB::beginTransaction();
			$_login_at = Carbon::now()->toDateTimeString();
			/**
			 * write log
			*/
			$_log = new UserAccessLog;
			$_log->uid 			= $this->uid;
			$_log->user_name 	= $this->user_name;
			$_log->login_from 	= $login_from;
			$_log->login_ip 	= $_login_ip;
			$_log->login_ip_area= $_login_area;
			$_log->login_at 	= $_login_at;
				
			$_log->save();
	
			/**
			 * update user
			*/
			$this->login_ip 		= $_login_ip;
			$this->login_ip_area	= $_login_area;
			$this->login_at 		= $_login_at;
			$this->login_session 	= Session::getId();
			$this->save();
	
			DB::commit();
		}
		catch (\PDOException $e)
		{
			DB::rollBack();
		}
	
	}
	
	function is_vip()
	{
		return $this->user_level == User::LEVEL_VIP;
	}
	
	function vip_label_class()
	{
		return 'label-info';
	}
	
	function has_verified()
	{
		/**
		 * email 不做强制验证，但用户必须填写email
		 * 后面可以在用到email的时候再让用户验证
		 */
		if (!empty($this->mobile) &&
				//$this->is_email_verified &&
				$this->is_id_verified)
		{
			return true;
		}
			
		return false;
	}
	
	function has_all_verified()
	{
		if (!empty($this->mobile) &&
				$this->is_email_verified &&
				$this->is_id_verified &&
				!empty($this->withdraw_password))
		{
			return true;
		}
			
		return false;
	}
	
	function level_desc()
	{
		$_desc = '个人用户';
		if ($this->is_vip())
		{
			$_desc = 'Vip';
		}

		return $_desc;
	}
	
	function status_desc()
	{
		$_desc = '';
		switch ($this->status)
		{
			case User::STATUS_NORMAL:
				$_desc = '正常';
				break;
			case User::STATUS_DISABLED:
				$_desc = '已禁用';
				break;
			default:
				break;
		}
		
		return $_desc;
	}

	/**
	 * 获取用户验证信息, 如果为空就新建一个
	 *
	 * @return WeXchange\Model\Verification
	 */
	public function verify()
	{
		$_obj = Verification::find($this->uid);
		if (empty($_obj))	// null
		{
			$_obj 		= new Verification;
		}
	
		return $_obj;
	}

	/**
	 * 获取用户详细档案
	 *
	 * @return null/UserProfile
	 */
	function profile()
	{
		return UserProfile::find($this->uid);
	}
	
	/**
	 * 获取用户的平台余额
	 * 
	 * @return null/Balance
	 */
	public function balance()
	{
		return Balance::find($this->uid);
	}
	
	/**
	 * 获取用户的平台余额
	 *
	 * @return null/UserBankAccount
	 */
	public function bank_account()
	{
		return UserBankAccount::find($this->uid);
	}
	/**
	 * 用户所属角色拼接
	 *
	 *
	 * @return string
	 */
	public function role_name()
	{
		foreach ($this->roles as $k => $role)
        {
            $role_name[] = $role->name;
        }
	    return implode(',', $role_name);
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