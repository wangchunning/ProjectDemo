<?php namespace Controllers\Invest;

use View;
use Auth;
use Carbon;
use App;
use Controllers\BaseController;
use Controllers\AuthController;
use Breadcrumb;
use Input;
use Validator;
use Redirect;
use Hash;

use UserService;

use User;
use DebtTx;
use Repayment;
use TXSession;
use DebtInvest;
use Ret;
/**
 *  
 *
 */
class InvestController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     * 
     *
     * @return void
     */
    public function getIndex()
    {
        Breadcrumb::map(array('首页' => url('/')))->append('我要投资');


        $this->layout->content = View::make('invest.index', $this->data); 
    }  


    public function getPawnDetail($id)
    {

    	Breadcrumb::map(array('我要投资' => url('/invest')))->append('项目详情');
    
    	$_tx 			= DebtTx::findOrFail($id);
    	$_tx_profile	= $_tx->tx_profile();
    	$_user			= User::findOrFail($_tx->uid);
    
    	TXSession::init(DebtInvest::SESSION_NAME);
    	
    	$this->data['balance']	= login_user()->balance();
    	$this->data['tx']	= $_tx;
    	$this->data['user']	= $_user;
    	$this->data['approved_materials'] 	= $_tx->approved_materials_unique_type();
    	$this->data['profile'] 		= $_tx_profile;
    
    	$this->layout->content = View::make('debt.pawn.detail', $this->data);
    }
    
    
    public function postInvestAdd($tx_id)
    {
    	$_validator = Validator::make(Input::all(), array(
    			'invest_amount'     => 'required|integer',
    	));    	
    	if ($_validator->fails())
    	{
    		App::abort(404);
    	}
    	
    	$_invest				= new DebtInvest;
    	$_invest->tx_id			= $tx_id;
    	$_invest->invest_amount	= Input::get('invest_amount');
    	
    	/**
    	 * 保存信息到 session
    	 */
    	TXSession::init(DebtInvest::SESSION_NAME);
    	TXSession::put(DebtInvest::SESSION_NAME, $_invest);
    	
    	return Redirect::to('/invest/pawn-confirm');
    }

    public function getPawnConfirm()
    {
    	Breadcrumb::map(array('我要投资' => url('/invest')))->append('确认投资信息');
    	
    	if (!TXSession::check(DebtInvest::SESSION_NAME))
    	{
    		App::abort(404);
    	}
    	$_invest = TXSession::get(DebtInvest::SESSION_NAME);
    	
    	$_tx 			= DebtTx::findOrFail($_invest->tx_id);
    	$_tx_profile	= $_tx->tx_profile();
    	$_user			= User::findOrFail($_tx->uid);
    	$_gain_detail	= Repayment::calc_benefit($_invest->invest_amount, $_tx->period_month, $_tx->rate);
    	
    	$_invest->tx_title					= $_tx->title;
    	$_invest->tx_period 				= $_tx->period_month;
    	$_invest->interest_amount			= $_gain_detail['interest'];
    	$_invest->unpaid_principal_amount	= $_invest->invest_amount;   	
    	
    	TXSession::put(DebtInvest::SESSION_NAME, $_invest);
    	
    	$this->data['balance']	= login_user()->balance();
    	$this->data['tx']	= $_tx;
    	$this->data['user']	= $_user;
    	$this->data['approved_materials'] 	= $_tx->approved_materials_unique_type();
    	$this->data['profile'] 		= $_tx_profile;
    	$this->data['gain_detail']	= $_gain_detail;

    	
    	$this->layout->content = View::make('invest.pawn.confirm', $this->data);
    }

    
    public function postPawnConfirm()
    {
    	
    	$_validator = Validator::make(Input::all(), array(
    			'password'		=> 'required',
    	));
    	if ($_validator->fails())
    	{
    		return Redirect::back()->withErrors($_validator);
    	}
    	
    	if (!TXSession::check(DebtInvest::SESSION_NAME))
    	{
    		App::abort(404);
    	}  	 
    	$_invest = TXSession::get(DebtInvest::SESSION_NAME);
    	 
    	$_user 		= login_user();
    	if (!Hash::check(Input::get('password'), $_user->withdraw_password))
    	{
    		return Redirect::back()->with('error', '交易密码不正确');
    	}
    	
    	$_invest->investor_uid			= $_user->uid;
    	$_invest->investor_user_name	= $_user->user_name;
    	
    	$_ret = UserService::add_debt_invest($_invest);
    	if ($_ret === Ret::RET_FAILED)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，系统暂时无法处理您的请求，请稍后再试');
    	}
    	
    	TXSession::forget(DebtInvest::SESSION_NAME);
    	
    	return Redirect::to('/home');
    }
}