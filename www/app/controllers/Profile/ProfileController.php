<?php namespace Controllers\Profile;

use View;
use Auth;
use Validator;
use DB;
use Input;
use Redirect;
use Sms;
use Hash;
use Mail;
use Queue;
use Pincode;
use EmailProvider;

use Controllers\AuthController;
use Breadcrumb;

use Ret;
use User;
use UserProfile;

use UserService;

/**
 *  配置控制器
 *
 */
class ProfileController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.simple';

    /**
     * 显示 profile 页面
     *
     * bool  if edit?
     * @return void
     */
    public function getIndex($type = FALSE)
    {
    	$this->layout = View::make('layouts.dashboard');
    	
    	$_user 						= login_user();
        $this->data['user'] 		= $_user;
        $this->data['profile']		= $_user->profile();
        
        if ($type == 'edit') 
        {
            // set breadcrumb
            Breadcrumb::map(array('首页' => url('/'), '个人信息' => url('profile')))->append('编辑个人信息');

            $this->layout->content = View::make('profile.edit', $this->data);
            return;
        }
        
        Breadcrumb::map(array('首页' => url('/home')))->append('个人信息');
        
        $this->data['bank_account']	= $_user->bank_account();
        
        $this->layout->content = View::make('profile.index', $this->data);
    }

    /**
     * 提交 profile 修改
     * 
     * @return Redirect
     */
    public function postEdit($uid)
    {
    	Input::merge(array_map('trim', Input::all()));
    	
        $_validator = Validator::make(Input::all(), array(
            'education'         => "required",
            'marriage'    		=> 'required',
            'address'     		=> 'required',
            'company_category'  => 'required',
            'company_scale'   	=> 'required',
            'position'    		=> 'required',
            'salary' 			=> 'required',
        ));
        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }   

		$_profile = UserProfile::where('uid', $uid)->firstOrFail();          
		
		try
		{
			$_profile->education 		= Input::get('education');
			$_profile->marriage 		= Input::get('marriage');
			$_profile->address 			= Input::get('address');
			$_profile->company_category = Input::get('company_category');
			$_profile->company_scale 	= Input::get('company_scale');
			$_profile->position 		= Input::get('position');
			$_profile->salary 			= Input::get('salary');
			$_profile->education_school = Input::get('education_school', '');
			
	        $_profile->save();
		}
		catch (\PDOException $e)
		{
			return Redirect::back()->withInput()->with('error', '抱歉，暂时无法更新个人信息，请稍后重试');
		}
		
        return Redirect::to('profile')->with('message', '个人信息已更新');
    }

    /**
     * 编辑银行卡
     */
    public function getEditBankAccount()
    {
    	Breadcrumb::map(array('我的账户' => url('/home'), 
    							'个人信息' => url('/profile'))
    				)->append('修改提现银行卡信息');
    	
    	
    	$_user	= login_user();
    	$this->data['bank_account']	= $_user->bank_account();
    	$this->data['profile']		= $_user->profile();
    	
    	$this->layout = View::make('layouts.dashboard');
        $this->layout->content = View::make('profile.bank_account_edit', $this->data);
    }

    /**
     * 修改银行卡信息
     */
    public function postEditBankAccount()
    {
        Input::merge(array_map('trim', Input::all()));
    	
    	$_validator = Validator::make(Input::all(), array(
    		'bank_name'     => 'required',
			'bank_full_name'=> 'required',
    		'account_number'=> 'required|numeric',
    	));
    	
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validator);
    	} 

    	$_ret = UserService::save_bank_account(Input::get('bank_name'), 
    											Input::get('bank_full_name'), 
    											Input::get('account_number'));

    	if($_ret === Ret::RET_FAILED)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，系统暂时无法修改您的银行卡信息，请稍后再试');
    	}
    	
        return Redirect::to('/profile')->with('message', '银行卡信息已更新');
        
    }

    /**
     * 显示 change password 页面
     *
     * @return void
     */
    public function getChangePwd()
    {
    	Breadcrumb::map(array('首页' => url('/home'), '个人信息' => url('profile')))->append('修改登录密码');
    	
    	$this->layout = View::make('layouts.dashboard');
    	    	
        $this->layout->content = View::make('profile.changepwd');
    }

    /**
     * 提交 change password 修改
     * 
     * @return Redirect
     */
    public function postChangePwd()
    {
        
        $_validator = Validator::make(Input::all(), array(
            'old_password'  => "required",
            'password'      => 'required|min:8|confirmed'
        ));

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }   

        $_user = login_user();
        if (!Hash::check(Input::get('old_password'), $_user->login_password))
        {
            return Redirect::back()->with('error', '旧密码不匹配');
        }
        try
        {
	        $_user->login_password = Hash::make(Input::get('password'));
	        $_user->save();
        }
        catch (\PDOException $e)
        {
        	return Redirect::back()->withInput()->with('error', '抱歉，暂时无法更新登录密码，请稍后重试');
        }
        
        return Redirect::to('profile')->with('message', '登录密码已更新');
    }
    
    /**
     * 显示 change withdraw password 页面
     *
     * @return void
     */
    public function getSettingWithdrawPassword()
    {
    	Breadcrumb::map(array('首页' => url('/home'), '个人信息' => url('profile')))->append('设置交易密码');
    	 
    	$this->layout = View::make('layouts.dashboard');
    
    	$this->layout->content = View::make('profile.setting_withdraw_password');
    }
    /**
     * 交易密码
     */
    public function postSettingWithdrawPassword()
    {
    	$_validator = Validator::make(Input::all(), array(
    			'password'      => 'required|min:8|confirmed'
    	));
    
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validator);
    	}
    
    	$_user = login_user();

    	try
    	{
    		$_user->withdraw_password = Hash::make(Input::get('password'));
    		$_user->save();
    	}
    	catch (\PDOException $e)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，暂时无法更新交易密码，请稍后重试');
    	}
    
    	return Redirect::to('/profile')->with('message', '交易密码已更新');
    }
    /**
     * 修改交易密码
     *
     * @return void
     */
    public function getChangeWithdrawPassword()
    {
    	Breadcrumb::map(array('首页' => url('/home'), '个人信息' => url('profile')))->append('修改交易密码');
    	 
    	$this->layout = View::make('layouts.dashboard');
    
    	$this->layout->content = View::make('profile.change_withdraw_password');
    }
    
    /**
     * 提交 change password 修改
     *
     * @return Redirect
     */
    public function postChangeWithdrawPassword()
    {
    
    	$_validator = Validator::make(Input::all(), array(
    			'old_password'  => "required",
    			'password'      => 'required|min:8|confirmed'
    	));
    
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validator);
    	}
    
    	$_user = login_user();
    	if (!Hash::check(Input::get('old_password'), $_user->withdraw_password))
    	{
    		return Redirect::back()->with('error', '旧密码不匹配');
    	}
    	try
    	{
    		$_user->withdraw_password = Hash::make(Input::get('password'));
    		$_user->save();
    	}
    	catch (\PDOException $e)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，暂时无法更新交易密码，请稍后重试');
    	}
    
    	return Redirect::to('profile')->with('message', '交易密码已更新');
    }
    /**
     * 显示邮箱验证页面
     *
     * @return void
     */
    public function getEmailVerify()
    {
        $_user = login_user();

        $this->layout = View::make('layouts.dashboard');

        $this->data['email'] = EmailProvider::resolve($_user->email);;
        
        $this->layout->content = View::make('profile.email_verify', $this->data);                 
    }

    /**
     * 显示文件证明页面
     *
     * @return void
     */
    public function getVerify()
    {

        $_user = login_user();

        /**
         * 手机未验证
         */
        if (empty($_user->mobile)) 
        {
            return Redirect::to('profile/security')->with('unverified', TRUE);
        }

        if ($_user->is_id_verified)
        {
            return Redirect::to('/profile/complete');
        }    

        $this->data['user']     = $_user;

        $this->layout->content = View::make('profile.verify', $this->data);

    }

    /**
     * 提交文件证明
     *
     * @return Redirect
     */
    public function postVerify()
    {
        $_messages = array(
            'real_name.required' => '请填写真实姓名',
            'id_number.required' => '请填写身份证号'
        );

        Input::merge(array_map('trim', Input::all()));

        $_validator = Validator::make(Input::all(), array(
            'real_name'         => 'required',
            'id_number'         => 'required',
        ), $_messages);

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }  

        $_ret = UserService::id_verify(Input::get('real_name'), Input::get('id_number'));
        if ($_ret === false)	// 验证未通过
        {
        	return Redirect::back()->withInput()->with('error', '抱歉，您的真实姓名与身份证号不匹配，请重新输入');
        }
        else if ($_ret === Ret::RET_FAILED)
        {
        	return Redirect::back()->withInput()->with('error', '抱歉，我们暂时无法完成认证，请稍后重试');
        }  

        return Redirect::to('/profile/complete');         
    } 

    /**
     * 显示提交文件证明后提示页面
     *
     * @return void
     */
    public function getComplete()
    {

        $this->layout->content = View::make('profile.step_complete');                 
    }

    /**
     * 显示手机验证修改页面
     *
     * @return void
     */
    public function getChangeMobile()
    {$this->layout = View::make('layouts.dashboard');
        $user = Auth::member()->user();
        
        $this->layout->content = View::make('profile.mobile_change')->with('user', $user);                 
    }

    /**
     * 显示手机验证页面
     *
     * @return void
     */
    public function getSecurity()
    {
        $_user = login_user();
        /* 邮箱未验证
        if (!$_user->email_verified) 
        {
            return Redirect::to('profile/email-verify')->with('unverified', TRUE);
        }*/

        if (!empty($_user->mobile))
        {
            return Redirect::to('/profile/verify');
        } 
        
        $this->layout->content = View::make('profile.security')
                                    ->with('user', $_user);                 
    }  

    /**
     * 提交手机验证
     *
     * @return Redirect
     */
    public function postSecurity()
    {       
        $_messages = array(
            'mobile.required'   => '手机号码不能为空',
            'mobile.valid_tel'  => '手机号码格式不正确',            
            'pin.required'      => '请输入验证码',
            'pin.valid_pin'  	=> '验证码无效',
        );

        $_validator = Validator::make(Input::all(), array(
            'mobile'        => 'required|valid_tel',
            'pin'           => 'required|valid_pin',
        ), $_messages);

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }
        /**
         * 保存手机号
         */
        $_user          	= login_user();
        $_user->mobile      = Input::get('mobile');
        $_user->save();       

        /**
         * 如果第三步未上传文件则跳转到第三步上传图片
         */
        if (!$_user->is_id_verified) 
        {
            return Redirect::to('/profile/verify');
        }

        /**
         * 其它情况，如老用户不用验证身份id，跳转到完成页面
         */
        return Redirect::to('/profile/complete');      
    } 

    /**
     * 发送 PIN 到指定号码
     *
     * @return Response
     */
    public function postPin()
    {
        $_messages = array(
            'mobile.required' => '手机号码不能为空',
            'mobile.valid_tel' => '手机号码格式无效'
        );

        $_validator = Validator::make(Input::all(), array(
            'mobile'   => 'required|valid_tel',
        ), $_messages);

        if ($_validator->fails())
        {
            return $this->push('error', array('msg' => $_validator->messages()->first()));
        }

        $_phone_code 	= '86';
		$_mobile 		= Input::get('mobile');
        // 更新手机和验证 PIN 码
        Pincode::create();

        // 发送验证码到手机
        Pincode::send(
	        '+' . $_phone_code . $_mobile,
	        '欢迎使用天添财富！为了保证您的交易安全，请输入验证码：%s'
        );

        $_msg = sprintf('我们已将验证码发送到您的手机 **** *** %s', substr($_mobile, -4));

        return $this->push('ok', array('msg' => $_msg));
    }  

    /**
     * 显示账号升级说明页面
     *
     * @return void
     */
    public function getUpgrade()
    {
        $this->layout->content = View::make('profile.upgrade');                         
    }

    /**
     * 显示账号升级表单页面
     * 
     * @return void
     */
    public function getUpgradeBusiness()
    {
        $this->layout->content = View::make('profile.business');                                 
    }

    /**
     * 提交账号升级表单
     *
     * @return Redirect
     */
    public function postUpgradeBusiness()
    {
        $messages = array(
            'abn.abn'              => 'ABN/ACN 无效',            
            'city.required'        => '城市 不能为空.'
        );

        $validator = Validator::make(Input::all(), array(
            'business_name'          => 'required',
            'abn'                    => 'required|abn',
            'street_number'          => 'required',
            'street'                 => 'required',
            'city'                   => 'required',
            'state'                  => 'required',
            'postcode'               => 'required'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::to('profile/upgrade-business')->withInput()->withErrors($validator);
        }  

        $user = Auth::member()->user();

        // 保存企业信息
        $business = new Business;        
        $business->business_name = trim(Input::get('business_name'));
        $business->abn = trim(Input::get('abn'));
        $business->unit_number = trim(Input::get('unit_number'));
        $business->street_number = trim(Input::get('street_number'));
        $business->street = trim(Input::get('street'));
        $business->city = trim(Input::get('city'));
        $business->state = trim(Input::get('state'));
        $business->postcode = trim(Input::get('postcode'));
        $user->business()->save($business);

        // 更新账号类型
        $user->profile->type = 'business';
        $user->profile->save();

        return Redirect::to('home');
    }

    /**
     * 重新发送验证邮件
     * 
     * @return Response;
     */
    public function postSendVerifyEmail()
    {
    	$_messages = array(
    		'email.email'        => '请输入有效邮箱格式',
    	);
    	
    	Input::merge(array_map('trim', Input::all()));
    	
    	$_validator = Validator::make(Input::all(), array(
    		'email'         => 'required|email',
    	), $_messages);
    	
    	if ($_validator->fails())
    	{
    		return $this->push('error', array('msg' => $validator->messages()->first('email')));
    	}
    	
    	$_ret = UserService::send_verify_email(Input::get('email'));
    	if ($_ret == Ret::RET_EMAIL_DUPLICATED_VERIFY)
    	{
    		return $this->push('error', array('msg' => '您的邮箱已经通过验证，不需要重复验证'));
    	}
    	if ($_ret == Ret::RET_FAILED)
    	{
    		return $this->push('error', array('msg' => '抱歉，暂时无法完成邮箱验证，请稍后重试'));
    	}
    	
        return $this->push('ok', array('msg' => '验证邮件已发送，请您登录邮箱，点击验证链接'));  
    }

     /**
     * 发送验证邮件
     * 
     * @param $new_email
     * @return null;
     */
    public function resendEmail($new_email = NULL)
    {
        $cu = Auth::member()->user();

        if ($new_email) 
        {
            $cu->email = $new_email;
            $cu->save();
            $cu->verify->email_verified = 0;
        }

        $email = $cu->email;

        // 邮件验证 token
        $token = str_random(20);

        $cu->verify->email_verify_token = Hash::make($token);

        $cu->verify->save();

        $data = array(
            'user' => $cu->full_name,
            'email' => $email,
            'url' => url(sprintf("activate/confirm/%s/%s", $cu->uid, $token))            
        );

        Mail::queue('emails.welcome', $data, function($message) use ($email)
        {
            $message->to($email)->subject('验证邮件 - Anying');
        });      
    }
}
