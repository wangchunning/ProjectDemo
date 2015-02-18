<?php namespace Controllers\Admin;

use View;
use Validator;
use Input;
use Carbon;
use Notes;
use Auth;
use Redirect;
use Event;
use App;
use Request;
use BalanceHistory;
use Hash;
use Mail;
use DB;
use Password;

use Controllers\AdminController;

use User;
use UserProfile;

/**
 *  会员管理控制器
 *
 */
class CustomerController extends AdminController {

    /**
     * 显示用户列表
     *
     * @return void
     */
    public function getIndex($status = '')
    {
        // 权限检查
        check_perm('view_customer');
        
        $_users = User::get();

        $this->data['users'] = $_users;
        
        $this->layout->content = View::make('admin.customer.index', $this->data);
        
    }

    /**
     * 显示用户信息概要
     *
     * @return void
     */
    public function getOverview($uid)
    {
        // 权限检查
        check_perm('view_customer');

        $this->data['user'] 	= User::findOrFail($uid);
        $this->data['profile'] 	= UserProfile::find($uid);

        // 用户的余额信息
        $this->data['balances'] = array();

        // 用户的 receipts
        $this->data['receipts'] = array();;        

        // 用户的 notes
        $this->data['notes'] = array();;

        // 用户的 activity
        $this->data['activities'] = array();;             

        $this->layout->content = View::make('admin.customer.overview', $this->data);
    }

    public function getUserStatus($uid, $status)
    {
    	check_perm('write_customer');
    	
    	$_status = User::STATUS_NORMAL;
    	if ($status == 'disabled')
    	{
    		$_status = User::STATUS_DISABLED;
    	}
    	else if ($status == 'normal')
    	{
    		$_status = User::STATUS_NORMAL;
    	}
    	else 
    	{
    		App::abort(404);
    	}
    	
    	$_user = User::where('uid', $uid)->first();
    	if (empty($_user))
    	{
    		App::abort(404);
    	}
    	
    	$_user->status 	= $_status;
    	$_user->save();
    	
    	return Redirect::back();
    }
    
    
    
    
    
    
    
    
    
    /**
     * 用户的 receipts 列表
     *
     * @param  int  $uid
     * @return void
     */
    public function getReceipts($uid)
    {
        // 权限检查
        check_perm('view_customer');

        $this->data['user'] = User::withTrashed()->where('type', '=', 'user')->findOrFail($uid);

        // 用户的 receipts
        $this->data['receipts'] = $this->data['user']
             ->receipts()
             ->withTrashed()
             ->orderBy('created_at', 'desc')
             ->paginate(20);

        $this->layout->content = View::make('admin.customer.receipts', $this->data);
    }

    /**
     * 用户的 Activity 列表
     *
     * @param  int  $uid
     * @return void
     */
    public function getActivities($uid)
    {
        // 权限检查
        check_perm('view_customer');

        $this->data['user'] = User::withTrashed()->where('type', '=', 'user')->findOrFail($uid);

        // 用户的 receipts
        $this->data['activities'] = $this->data['user']
             ->customerActivities()        
             ->orderBy('created_at', 'desc')
             ->paginate(20);

        $this->layout->content = View::make('admin.customer.activity', $this->data);
    }

    /**
     * 用户某种货币的流水记录
     *
     * @param  int     $uid
     * @param  string  $currency
     * @return void
     */
    public function getBalance($uid, $currency)
    {
        // 权限检查
        check_perm('view_customer');

        $this->data['user'] = User::withTrashed()->where('type', '=', 'user')->findOrFail($uid);        

        if ( ! $currency)
        {
            App::abort(404, 'Page not found');          
        }

        if ( ! $this->data['balance'] = $this->data['user']->balances()->where('currency', '=', $currency)->get()->first())
        {
            App::abort(404, 'Page not found');
        }

        $this->data['histories'] = BalanceHistory::where('uid', '=', $this->data['user']->uid)
                                    ->where('currency', '=', $currency)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(20);

        $this->layout->content = View::make('admin.customer.balance', $this->data);
    }

    /**
     * 登录进用户管理平台
     *
     * @param  int  $uid
     * @return void
     */
    public function getLoginToManage($uid)
    {
        // 权限检查
        check_perms('view_customer,login_to_manage');

        $user = User::withTrashed()->where('type', '=', 'user')->findOrFail($uid);

        $user->save_access_log();

        /**
         * Admin 只能以personal/business身份管理账户，
         * 不能以 business member 身份管理账户
         */
        customer_login_type('personal');

        Auth::member()->login($user);

        // add log
        Activity::log(array(
            'obj_id'        => $user->uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => 'Login',
            'description'   => 'Login to ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name)
        ));

        return Redirect::to('home');
    }

    /**
     * 提交用户信息验证
     * 
     *
     * @return Response
     */
    public function postVerify()
    { 
        // 权限检查
        check_perms('view_customer,verify_customer');

        $user = User::withTrashed()->find(Input::get('uid'));

        $user->verify->{Input::get('type') . '_verified'} = Input::get('action') == 'verify' ? 1 : 0;
        $user->verify->save();

        $verify_type = Input::get('type');

        // 触发邮件通知
        switch (Input::get('action')) 
        {
            case 'verify':
                // 重置发送提醒
                if ($verify_type == 'photo_id') 
                {
                    $user->verify->photo_id_request = '';
                }
                else
                {
                    $user->verify->addr_proof_request = '';
                }
                $user->verify->save();

                if ($user->verify->photo_id_verified AND $user->verify->addr_proof_verified) 
                {
                    $data = array(
                        'user' => $user->full_name,
                        'url' => url('/')            
                    );

                    $email = $user->email;

                    Mail::queue('emails.account_verified', $data, function($message) use ($email)
                    {
                        $message->to($email)->subject('Account Verified');
                    });

                    // 首次三步验证未完成？
                    if ( ! $user->profile->guided AND $user->verify->email_verified AND $user->verify->security_verified) 
                    {
                        $user->profile->guided = 1;
                        $user->profile->save();
                    }
                }
                break;
            
            default:
                // 标识发送提醒时间
                if ($verify_type == 'photo_id') 
                {
                    $user->verify->photo_id_request = time();
                }
                else
                {
                    $user->verify->addr_proof_request = time();
                }
                $user->verify->save();

                $data = array(
                    'user' => $user->full_name,
                    'type' => $verify_type == 'photo_id' ? 'Photo ID' : 'Proof of Address',
                    'url' => url('/profile/verify') 
                );

                $email = $user->email;

                Mail::queue('emails.account_resend', $data, function($message) use ($email)
                {
                    $message->to($email)->subject('Please Re-upload Your Photo Documents');
                });
                
                break;
        }

        // add log
        Activity::log(array(
            'obj_id'        => $user->uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => Input::get('action') == 'verify' ? 'Verified' : 'Re-verify',
            'description'   => 'Customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name) . ' Document'
        ));
        
        return $this->push('ok');
    }

    /**
     * ajax 重发激发邮件
     *
     * @return Response
     */
    public function postResendVerifyEmail()
    {
        // 权限检查
        check_perm('view_customer');

        $cu = User::find(Input::get('uid'));

        // 邮件验证 token
        $token = str_random(20);

        $cu->verify->email_verify_token = Hash::make($token);
        $cu->verify->save();

        $data = array(
            'user' => $cu->full_name,
            'email' => $cu->email,
            'url' => url(sprintf("activate/confirm/%s/%s", $cu->uid, $token))            
        );

        $email = $cu->email;

        Mail::queue('emails.welcome', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Verification Email');
        });

        // add log
        Activity::log(array(
            'obj_id'        => $cu->uid,
            'customer_id'   => $cu->uid,
            'activity_type' => 'Customers',
            'action'        => 'Resend Email',
            'description'   => 'Resend verify email to ' . link_to('/admin/customers/overview/' . $cu->uid, $cu->full_name)
        ));

        return $this->push('ok', array('msg' => 'We have send a verification email to this user.'));    

    }

    /**
     * ajax 发送重设置密码邮件
     *
     * @return Response
     */
    public function postResetPasswordEmail()
    {
        // 权限检查
        check_perm('view_customer');

        $user = User::find(Input::get('uid'));

        Password::remind(array('email' => $user->email), function($message, $user)
        {
            $message->subject('WeXchange Password reset');
        });

        // add log
        Activity::log(array(
            'obj_id'        => $user->uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => 'Password',
            'description'   => 'Send a password reset email to ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name)
        )); 

        return $this->push('ok', array('msg' => 'We have send a password reset email to this user.'));    
    }

    /**
     * 显示 profile 信息
     *
     * @param  int   $uid
     * @return void
     */
    public function getEdit($uid)
    {
        // 权限检查
        check_perms('view_customer,edit_customer');

        $user = User::withTrashed()->find($uid);
        $this->layout->content = View::make('admin.customer.edit')->with('user', $user);
    }

    /**
     * 提交 profile 修改
     * 
     * @param  int   $uid
     * @return Redirect
     */
    public function postEdit($uid)
    {
        // 权限检查
        check_perms('view_customer,edit_customer');

        $user = User::withTrashed()->find($uid);

        $messages = array(
            'email.unique'            => 'The :attribute has already been taken.',
            'birth_day.dateformat'    => 'The day of birth is not a valid format.',
            'birth_month.dateformat'  => 'The month of birth is not a valid format.',
            'birth_year.dateformat'   => 'The year of birth is not a valid format.',
            'city.required'           => 'The suburb or city is required.'
        );

        $validator = Validator::make(Input::all(), array(
            'email'         => "required|email|unique:users,email,$user->uid,uid",
            'first_name'    => 'required',
            'last_name'     => 'required',
            'birth_day'     => 'required|dateformat:d',
            'birth_month'   => 'required|dateformat:m',
            'birth_year'    => 'required|dateformat:Y',
            'street_number' => 'required',
            'street'        => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'postcode'      => 'required'
        ), $messages);

        if ($validator->fails())
        {
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }   

        try
        {
            /**
            * begin MySQL transaction
            *
            * All queries must be in one transaction
            */
            DB::beginTransaction();
            // 更换了新的邮箱？需要重新验证邮箱
            if ($user->email !== Input::get('email'))
            {  
                $user->verify->email_verified = 0;
                $user->verify->save();
            }          

            foreach (array('email', 'first_name', 'middle_name', 'last_name') as $field)
            {
                $user->$field = trim(Input::get($field));               
            }
            $user->save();

            $items = array('vip_type', 'birth_day', 'birth_month', 'birth_year', 'unit_number', 'street_number', 'street', 'city', 'state', 'postcode');

            // 检查权限
            if ( ! check_perm('upgrade_vip', FALSE)) 
            {
                unset($items[0]);
            }

            foreach ($items as $field)
            {
                $user->profile->$field = trim(Input::get($field));   
            }            
            $user->profile->save();

            // add log
            Activity::log(array(
                'obj_id'        => $user->uid,
                'customer_id'   => $user->uid,
                'activity_type' => 'Customers',
                'action'        => 'Updated',
                'description'   => 'Updated Customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name) . ' information'
            ));

            /**
             * end MySQL transaction
             */
            DB::commit();
        }
        catch (\PDOException $e)
        {
            /* rollback */
            DB::rollBack();
            return $this->push('error', array('msg' => 'Transaction failed, please try again.'));
        }

        return $this->push('ok');
    }

    /**
     * 软删除用户
     *
     * @param  int   $uid 
     * @return Response
     */
    public function getRemove($uid)
    {
        // 权限检查
        check_perms('view_customer,archive_customer');

        if ( ! $user = User::find($uid)) 
        {
            return $this->push('error');
        }

        $user->delete();

        // add log
        Activity::log(array(
            'obj_id'   => $uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => 'Delete',
            'description' => 'Delete customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name)
        ));

        return $this->push('ok');
    }

    /**
     * 恢复软删除的用户
     *
     *
     * @param  int   $uid 
     * @return Response
     */
    public function getRestore($uid)
    {
        // 权限检查
        check_perms('view_customer,activate_customer');

        if ( ! $user = User::onlyTrashed()->find($uid)) 
        {
            return $this->push('error');
        }

        $user->restore();

        // add log
        Activity::log(array(
            'obj_id'   => $uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => 'Restore',
            'description' => 'Restore customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name)
        ));

        return $this->push('ok');
    }

    /**
     * 导出 CSV
     *
     * @return void
     */
    public function getExport($status = '')
    {
        // 权限检查
        check_perm('view_customer');

        header('Content-Type: application/vnd.ms-excel'); 
        header(sprintf('Content-Disposition: attachment;filename="Customers-%s.csv"', Carbon::now()->toDateString())); 
        header('Cache-Control: max-age=0'); 

        $users = User::status($status)
                     ->type(Input::get('type'))
                     ->timearea(Input::get('dw'), Input::get('start'), Input::get('expriy'))
                     ->get(); 

        $head = array('Name', 'Email', 'Total Value', 'Last Activity', 'Status');

        $fp = fopen('php://output', 'a');

        fputcsv($fp, $head); 

        foreach($users as $i => $user)
        {
            if ($i % 10000 == 0)
            {
                ob_flush();
                flush();
            }

            fputcsv($fp, array(
                $user->full_name,
                $user->email,
                'N/A',
                date_word($user->last_login),
                $user->status
            ));
        }

        exit;
    }

    /**
     * 提交 business 修改
     * 
     * @param  int   $uid
     * @return Redirect
     */
    public function postEditBusiness($uid)
    {
        // 权限检查
        check_perms('view_customer,upgrade_business');

        $user = User::withTrashed()->find($uid);

        $messages = array(
            'abn.abn'              => 'Then ABN/ACN is not a valid number.',            
            'city.required'        => 'The suburb or city is required.'
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
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }   

        try
        {
            /**
            * begin MySQL transaction
            *
            * All queries must be in one transaction
            */
            DB::beginTransaction();

            // 保存企业信息        
            if (!empty($user->business)) 
            {
                $user->business->business_name = trim(Input::get('business_name'));
                $user->business->abn = trim(Input::get('abn'));
                $user->business->unit_number = trim(Input::get('unit_number'));
                $user->business->street_number = trim(Input::get('street_number'));
                $user->business->street = trim(Input::get('street'));
                $user->business->city = trim(Input::get('city'));
                $user->business->state = trim(Input::get('state'));
                $user->business->postcode = trim(Input::get('postcode'));
                $user->business->save();

                // add log
                Activity::log(array(
                    'obj_id'        => $user->uid,
                    'customer_id'   => $user->uid,
                    'activity_type' => 'Customers',
                    'action'        => 'Upgraded',
                    'description'   => 'Edit business customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name) . ' info'
                ));
            }
            else
            {
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

                /**
                 * update user profile type
                 */
                $user->profile->type = 'business';
                $user->profile->save();

                // add log
                Activity::log(array(
                    'obj_id'        => $user->uid,
                    'customer_id'   => $user->uid,
                    'activity_type' => 'Customers',
                    'action'        => 'Upgraded',
                    'description'   => 'Upgrade customer ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name) . ' to business'
                ));
            }

            /**
             * end MySQL transaction
             */
            DB::commit();
        }
        catch (\PDOException $e)
        {
            /* rollback */
            DB::rollBack();
            return $this->push('error', array('msg' => 'Transaction failed, please try again.'));
        }

        return $this->push('ok');
    }


    /**
     * ajax 发送重设置密码邮件
     *
     * @return Response
     */
    public function postRemindInactiveCustomer($uid)
    {
        // 权限检查
        check_perm('edit_customer');
        
        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        $user = User::find($uid);
        if (!$user)
        {
            $msg = "invalid customer";
            return $this->push('error', array('msg' => $msg));
        }

        $email = $user->email;

        $data = array(
            'user'   => $user->first_name,
            'url'       => url("/login")
        );

        Mail::send('emails.remind_inactive_customer', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Welcome back to Wexchange');
        });

        // add log
        Activity::log(array(
            'obj_id'        => $user->uid,
            'customer_id'   => $user->uid,
            'activity_type' => 'Customers',
            'action'        => 'Email Inactive Customer',
            'description'   => 'Email inactive customer to ' . link_to('/admin/customers/overview/' . $user->uid, $user->full_name)
        )); 

        return $this->push('ok', array('msg' => 'Email sent'));    
    }

}
