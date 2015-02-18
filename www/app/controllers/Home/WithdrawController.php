<?php namespace Controllers\Home;

use View;
use Auth;
use Input;
use Validator;
use Redirect;
use Hash;

use Pincode;
use Controllers\AuthController;
use Breadcrumb;

use Ret;
use Fee;
use WithdrawTx;
use TXSession;
use UserService;
/**
 *  Withdraw Controller
 */
class WithdrawController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     *
     */
    public function getIndex()
    {
    	App::abort(404);
    	
        $user = login_user();

        $this->data['balances'] = $user->balance();

        $userType = $user->profile->vip_type;

        $this->data['rates'] = Exchange::query($user->balance_currency, $userType);       

        $this->layout->content = View::make('lockrate.withdraw.index', $this->data);
    }   

    /**
     * Add a withdraw
     *
     */
    public function getAdd()
    {
    	TXSession::init(WithdrawTx::SESSION_NAME);
    	
        $_user = login_user();
        /**
         * 还没设置取款密码？
         */
        if (empty($_user->withdraw_password))
        {
        	return Redirect::to('/profile/setting-withdraw-password')->with('error', '为了您的资金安全，提现前请先设置交易密码');
        }
        
        $_balance = $_user->balance();

        if (empty($_balance) ||
        		$_balance->available_amount == 0)
        {
        	return Redirect::back()->with('error', '抱歉，您的可用余额不足，无法提现');
        }
        
        // set breadcrumb
        Breadcrumb::map(array('我的账户' => url('/home')))->append('提现');
        
        // 余额列表
        $this->data['balance'] 	= $_balance;
        $this->data['fee'] 		= Fee::withdraw_fee();
        
        // 收款账号
        $this->data['bank_account'] = $_user->bank_account();
        $this->data['profile'] 		= $_user->profile();
        
        $this->layout->content = View::make('home.withdraw.add', $this->data);        
    } 

    /**
     * Post a withdraw
     *
     * @return Redirect
     */
    public function postAdd()
    {
    	$_messages = array(

    	);
    	
    	Input::merge(array_map('trim', Input::all()));
    	
    	$_validator = Validator::make(Input::all(), array(
    		'amount'		=> 'required|numeric',
    		'fee'      		=> 'required|numeric',
    		'real_amount'   => 'required|numeric',
    		'bank_name'     => 'required',
			'bank_full_name'=> 'required',
    		'account_number'=> 'required|numeric',
    			
    	), $_messages);
    	
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validator);
    	}

    	$_user 		= login_user();
    	$_profile	= $_user->profile();
    	$_balance 	= $_user->balance();
    	
    	if ($_balance->available_amount < Input::get('real_amount'))
        {
    		return Redirect::back()->withInput()->with('error', '您的账户余额不足');
    	}
    	
    	$_tx					= new WithdrawTx;
    	$_tx->uid				= $_user->uid;
    	$_tx->bank_name			= Input::get('bank_name');
    	$_tx->bank_full_name	= Input::get('bank_full_name');
    	$_tx->account_name		= $_profile->real_name;
    	$_tx->account_number	= Input::get('account_number');
    	$_tx->total_amount		= Input::get('real_amount');
    	$_tx->available_amount	= Input::get('amount');
    	$_tx->fee_amount		= Input::get('fee');
    	
		/**
         * 保存订单信息到 session
         */
        TXSession::init(WithdrawTx::SESSION_NAME);
        TXSession::put(WithdrawTx::SESSION_NAME, $_tx);
        
        return Redirect::to('/withdraw/confirm');        
    }

    /**
     * Confirm for a withdraw
     * 
     * @return Redirect
     */
    public function getConfirm()
    {

    	// set breadcrumb
    	Breadcrumb::map(array(
    		'我的账户' => url('/home'),
    		'提现' => url('/withdraw/add/'))
    	)->append('确认提现信息');
    	
    	if (!TXSession::check(WithdrawTx::SESSION_NAME))
    	{
    		App::abort(404);
    	}
    	
    	$_tx = TXSession::get(WithdrawTx::SESSION_NAME);
    	
    	
    	$_user 		= login_user();
    	$_balance	= $_user->balance();
    	// double check
    	if ($_balance->available_amount < $_tx->real_amount)
    	{
    		return Redirect::to('/withdraw/add')->withInput()->with('error', '您的账户余额不足');
    	}
    	
    	$this->data['balance'] 		= $_balance;
    	$this->data['profile'] 		= $_user->profile();
    	$this->data['tx']			= $_tx;
    	
    	$this->layout->content = View::make('home.withdraw.confirm', $this->data);     
    }

    /**
     * Post form confirm
     *
     * @return Redirect
     */
    public function postConfirm()
    {
 
    	//Input::merge(array_map('trim', Input::all()));
    	 
    	$_validator = Validator::make(Input::all(), array(
    		'password'		=> 'required',	 
    	));
    	 
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validator);
    	}

    	
    	if (!TXSession::check(WithdrawTx::SESSION_NAME))
    	{
    		App::abort(404);
    	}	 
    	$_tx = TXSession::get(WithdrawTx::SESSION_NAME);
    	
    	$_user 		= login_user();
    	if (!Hash::check(Input::get('password'), $_user->withdraw_password)) 
    	{
    		return Redirect::back()->withInput()->with('error', '交易密码不正确');
    	}	    	 
    	
    	$_ret = UserService::add_withdraw($_tx->total_amount, $_tx->available_amount, 
    										$_tx->fee_amount, $_tx->bank_name, 
    										$_tx->bank_full_name, 
    										$_tx->account_name, $_tx->account_number);
    	if ($_ret === Ret::RET_FAILED)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，系统暂时无法处理您的提现请求，请稍后再试');
    	}
    	
    	TXSession::forget(WithdrawTx::SESSION_NAME);
    	
        return Redirect::to('/home')->with('message', '您的提现请求已成功提交，我们会在 3 个工作日内完成处理，请您耐心等待');
    } 
}