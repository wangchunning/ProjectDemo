<?php namespace Controllers\Login;

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
use Exchange;
use Cookie;
use Response;
use Request;

use Ret;
use UserLoginLog;

use LoginService;
/**
 *  登录控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class LoginController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.simple';	

    /**
     * User model
     *
     * @var Illuminate\Auth\UserInterface
     */
    protected $user;

    /**
     * Inject user model to the current container
     *
     * @param  Illuminate\Auth\UserInterface  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        // 判断缓存的链接是否后台系统
        if (Request::is('admin/*'))
        {
            Session::forget('url.intended');
        }
        
        $this->beforeFilter(function()
        {
            if (Request::is('logout'))
            {
                return;       
            }
            if (is_login())
            {
                return Redirect::to('/home');
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
    	$this->layout->content = View::make('login.index');
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

        Session::forget('last_activity');

        $_user_name  = Input::get('user_name');
        $_password   = Input::get('password');
        
        if (LoginService::login($_user_name, $_password, 
        		UserLoginLog::LOGIN_FROM_WEB) != Ret::RET_FAILED)
        {   
            // 记住帐户名
            $_remember 	= Input::get('remember', 0);
            $_r_time 	= 14820; 	// 60 * 24 * 7;
            if ($_remember) 
            {
                Cookie::queue('user_name', Input::get('user_name'), $_r_time);
                Cookie::queue('remember', $_remember, $_r_time);
            }
            else
            {
                Cookie::queue(Cookie::forget('user_name'));
                Cookie::queue(Cookie::forget('remember'));
            }

            // Authentication passed.redirect to previous page
            return Redirect::intended('/home');
        }
 
        // Authentication failed.
        return Redirect::back()->withInput()->with('error', '用户名或密码不正确');
    }

    /**
     * 登录或引导注册页面
     * 
     * @return \Response
     */
    public function getLoginOrSignup()
    {
        $this->layout->content = View::make('login.index');
    }

    /**
     * 退出登录
     *
     * @return Redirect
     */
    public function getLogout()
    {
        Auth::client()->logout();

        return Redirect::to('login');
    }

    /**
     * Session time out
     * 
     * @return \Response
     */
    public function getTimeout()
    {
        $this->layout->content = View::make('login.index')
        							->with('expired', "Session 已过期，请您重新登录");
    }
}
