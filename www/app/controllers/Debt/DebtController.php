<?php namespace Controllers\Debt;

use View;
use Carbon;
use App;

use Controllers\AuthController;
use Breadcrumb;
use Input;
use Validator;
use Redirect;

use User;
use UserProfile;

use DebtTx;
use DebtTxProfile;
use DebtTxMaterial;
use CreditLevel;
use TXSession;

use Material;
use Tx;
use Repayment;

use Ret;
use UserService;
/**
 *  
 *
 */
class DebtController extends AuthController {

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
    	App::abort(404);
        //$this->layout->content = View::make('debt.non_perform_asset.add', $this->data);       
    }  

    public function getPawn()
    {
    	return Redirect::to('/debt/pawn-add');
    }
    /**
     * 
     */
    public function getPawnAdd()
    {                                                             
        // set breadcrumb
        Breadcrumb::map(array('我要借款' => url('/debt'), 
        					'抵押贷' => '/debt/pawn')
        			)->append('填写借款申请信息');

        $_user = login_user();
        
        TXSession::init(DebtTx::SESSION_NAME);
        
        $this->data['user']			= $_user;
		$this->data['tx_config'] 	= Tx::config(Tx::PRODUCT_TYPE_B2P);
		$this->data['credit_config']= CreditLevel::config();
		
        $this->layout->content = View::make('debt.pawn.add', $this->data);
    }  

    /**
     *
     */
    public function postPawnAdd()
    {          
    	
    	Input::merge(array_map('trim', Input::all()));
    	
        $_validator = Validator::make(Input::all(), array(
            'title'             => 'required',
            'amount'      		=> 'required|money_amount',
            'period'    		=> 'required',
            'rate'          	=> 'required',
            'desc'              => 'required|max:500',
        ));

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }  
        
        $_user	= login_user();
        
        $_tx_config 	= Tx::config(Tx::PRODUCT_TYPE_B2P);
        $_credit_config = CreditLevel::config();
        
		$_tx 				= new DebtTx;
		$_tx_profile		= new DebtTxProfile;
		
		$_tx->uid			= $_user->uid;
		$_tx->user_name		= $_user->user_name;
		$_tx->title			= Input::get('title');
		$_tx->total_amount	= Input::get('amount');
		$_tx->period_month	= Input::get('period');
		$_tx->rate			= Input::get('rate');	
		$_tx->repayment_in_advance_rate	= $_tx_config['repayment_in_advanced_rate'];
		$_tx->repayment_type			= $_tx_config['repayment_type'];
		//$_tx->validate_date				= Carbon::now()->addDays($_tx_config['valid_day_cnt'])->toDateString();
		
		
		$_ret = Repayment::calc_repayment($_tx->total_amount, $_tx->period_month, 
											$_tx->rate,
        									$_tx->uid, Tx::PRODUCT_TYPE_B2P);

		$_tx->month_repayment_investor_amount 	= $_ret['month_repay_to_investor'];
		$_tx->month_repayment_fee_amount 		= $_ret['month_repay_fee'];
		$_tx->month_repayment_total_amount 		= $_tx->month_repayment_investor_amount + $_tx->month_repayment_fee_amount;
		$_tx->fee_amount 						= $_ret['service_fee'];
		$_tx->frozen_amount 					= $_ret['frozen_amount'];
		$_tx->available_amount = round($_tx->total_amount - $_tx->frozen_amount - $_tx->fee_amount, 2);
		
		$_tx_profile->desc	= Input::get('desc');
		$_tx->tx_profile	= $_tx_profile;
        /**
         * 保存订单信息到 session
         */
        TXSession::init(DebtTx::SESSION_NAME);
        TXSession::put(DebtTx::SESSION_NAME, $_tx);
        
        return Redirect::to('/debt/pawn-add-profile');      
    }


    /**
     * 
     */
    public function getPawnAddProfile()
    {                                 
    	Breadcrumb::map(array('我要借款' => url('/debt'),
    						'抵押贷' => '/debt/pawn')
    				)->append('填写公司信息');

        $this->layout->content = View::make('debt.pawn.add_profile', $this->data);
    }  

    /**
     *
     * @return Redirect
     */
    public function postPawnAddProfile()
    {
    	Input::merge(array_map('trim', Input::all()));
    	
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
            'register_date'     => 'required|date'
        ));

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }  
        
        if (!TXSession::check(DebtTx::SESSION_NAME))
        {
        	App::abort(404);
        }
         
        $_tx = TXSession::get(DebtTx::SESSION_NAME);
        $_tx_profile	= $_tx->tx_profile;
        
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
        
        TXSession::put(DebtTx::SESSION_NAME, $_tx);
        
        //TXSession::forget(DebtTx::SESSION_NAME);
        return Redirect::to('/debt/pawn-add-material');      
    }


    /**
     * 
     */
    public function getPawnAddMaterial()
    {                                 
    	Breadcrumb::map(array('我要借款' => url('/debt'),
    						'抵押贷' => '/debt/pawn')
    				)->append('上传认证材料');

        $this->layout->content = View::make('debt.pawn.add_material', $this->data);
        
    }  

    /**
     *
     * @return Redirect
     */
    public function postPawnAddMaterial()
    {
    	//Input::merge(array_map('trim', Input::all()));
    	 
        $_messages = array(
            'company_files.required'	=> '请上传公司认证材料',
        	'credit_files.required'		=> '请上传信用认证材料',
        );
        $_validator = Validator::make(Input::all(), array(
            'company_files'     => 'required',
            'credit_files'      => 'required',
        ), $_messages);

        if ($_validator->fails())
        {
        	return Redirect::back()->withInput()->withErrors($_validator);
        }

        if (!TXSession::check(DebtTx::SESSION_NAME))
        {
        	App::abort(404);
        }
        
        $_tx = TXSession::get(DebtTx::SESSION_NAME);
        
        foreach (Input::get('company_files') as $_file_path)
        {        
	        $_material 				= new DebtTxMaterial;
	        $_material->type		= Material::type_desc(Material::TYPE_COMPANY);
	        $_material->name		= basename($_file_path); // 文件名
	        $_material->path		= $_file_path;
	        
	        $_tx->tx_materials[]	= $_material;
        }
        
        foreach (Input::get('credit_files') as $_file_path)
        {
        	$_material 				= new DebtTxMaterial;
        	$_material->type		= Material::type_desc(Material::TYPE_CREDIT_REPORT);
        	$_material->name		= basename($_file_path); // 文件名
        	$_material->path		= $_file_path;
        	 
        	$_tx->tx_materials[]	= $_material;
        }
    
    	TXSession::put(DebtTx::SESSION_NAME, $_tx);
    
    	//TXSession::forget(DebtTx::SESSION_NAME);
    	return Redirect::to('/debt/pawn-confirm');
    }    

    /**
     * Confirm for a withdraw
     *
     * @return Redirect
     */
    public function getPawnConfirm()
    {
    
    	// set breadcrumb
    	Breadcrumb::map(array('我要借款' => url('/debt'),
    						'抵押贷' => '/debt/pawn')
    				)->append('确认借款信息');
    	 
    	if (!TXSession::check(DebtTx::SESSION_NAME))
    	{
    		App::abort(404);
    	}
    	 
    	$_tx = TXSession::get(DebtTx::SESSION_NAME);

    	$_user 		= login_user();
    	 
    	$this->data['materials'] 	= $_tx->tx_materials;
    	$this->data['profile'] 		= $_tx->tx_profile;
    	$this->data['tx']			= $_tx;
    	 
    	$this->layout->content = View::make('debt.pawn.confirm', $this->data);
    }
    
    /**
     * Post form confirm
     *
     * @return Redirect
     */
    public function postPawnConfirm()
    {  
    	 
    	if (!TXSession::check(DebtTx::SESSION_NAME))
    	{
    		App::abort(404);
    	}
    	
    	$_tx = TXSession::get(DebtTx::SESSION_NAME);

    	$_ret = UserService::add_debt($_tx);
    	if ($_ret === Ret::RET_FAILED)
    	{
    		return Redirect::back()->withInput()->with('error', '抱歉，系统暂时无法处理您的提现请求，请稍后再试');
    	}

    	TXSession::forget(DebtTx::SESSION_NAME);
    	 
    	return Redirect::to('/mydebt/pawn-detail/' . $_ret);
    }
    

}