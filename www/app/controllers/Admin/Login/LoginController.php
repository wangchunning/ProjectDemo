<?php namespace Controllers\Admin;

use Controllers\BaseController;
use View;
use Auth;
use Redirect;
use Validator;
use Input;
use Route;
use User;
use Event;
use Session;
use Request;

use Ret;
use Administrator;
use UserLoginLog;

use LoginService;

/**
 *  登录控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class LoginController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.admin_login';   

    /**
     * User model
     *
     * @var WeXchange\Model\User
     */
    protected $user;

    /**
     * Inject user model to the current container
     *
     * @param  Illuminate\Auth\UserInterface  $user
     * @return void
     */
    public function __construct(Administrator $user)
    {
        $this->user = $user;

        // 判断缓存的链接是否后台系统
        if (!Request::is('admin/*'))
        {
            Session::forget('url.intended');
        }

        $this->beforeFilter(function()
        {
            if (Request::is('admin/logout'))
            {
                return;       
            }
            if (is_login(Administrator::LABEL))
            {
                return Redirect::to('admin/center');
            }
        });
    }

    /**
     * 显示登录页面
     * 
     * @return \Response
     */
    public function getIndex()
    {
    	$this->layout->content = View::make('admin.login.index');
    }

    /**
     * Login a user
     *
     * @return Redirect
     */
    public function postIndex()
    {
    	$messages = array(
    			'user_name.required'      	=> '请输入用户名',
    			'password.required'      	=> '请输入密码',
    			'limit_login' 				=> '您的登录过于频繁，请 1 小时后再尝试登录',
    	);
    	
    	$validation = Validator::make(Input::all(), array(
    			'user_name'     => 'required|limit_login',
    			'password'      => 'required',
    	), $messages);
    	
    	if ($validation->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($validation);
    	}

    	$_user_name = Input::get('user_name');
    	$_password 	= Input::get('password');
    	
        Session::forget('last_activity');

        if (LoginService::login($_user_name, $_password, 
        		UserLoginLog::LOGIN_FROM_ADMIN) != Ret::RET_FAILED)
        {
        	// Authentication passed.redirect to previous page
        	return Redirect::intended('admin/center');
        }
        
        // Authentication failed.
        return Redirect::back()->withInput()->with('error', '用户名或密码不正确');
    }

	/**
	* 退出登录
	*
	* @return Redirect
	*/
	public function getLogout()
	{
		Auth::admin()->logout();

		return Redirect::to('admin/login');
	}
    

    /**
     * Session time out
     * 
     * @return \Response
     */
    public function getTimeout()
    {
        $this->layout->content = View::make('admin.login.index')
        							->with('expired', "Session 已过期，请您重新登录");
    }
}
