<?php namespace Controllers\Home;

use View;
use Auth;
use Input;
use Validator;
use Redirect;
use Session;
use Receipt;
use Controllers\AuthController;
use Breadcrumb;
use Request;
use App;

use Fee;
use Ret;
use UserService;
use FundInterface;
/**
 *  存款控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class FundController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     * Display fund list page
     *
     * @return void
     */
    public function getIndex()
    {        
    	App::abort(404);
    	// set breadcrumb
        Breadcrumb::map(array('我的账户' => url('/home')))->append('充值');
        
        $this->layout->content = View::make('home.fund.index', $this->data);          
    }

    /**
     * Display add fund page 
     *
     * @return void
     */
    public function getAdd()
    {
        // set breadcrumb
        Breadcrumb::map(array('我的账户' => url('/home')))->append('充值');

        $_user = login_user();
        
        $this->data['balance']	= $_user->balance();
        
        $this->data['fee'] 		= Fee::fund_fee();
        
        $this->layout->content = View::make('home.fund.add', $this->data);                  
    }

    /**
     * Post add fund
     *
     * @return Redirect
     */
    public function postAdd()
    {
        $_validation = Validator::make(Input::all(), array(
            'amount'      => 'required',
            'real_amount' => 'required',
            'fee_amount'  => 'required',
        ));

        if ($_validation->fails())
        {
            //return Redirect::back()->withInput()->withErrors($_validation);
            return App::abort(404);
        } 

        $_total_amount	= Input::get('amount');
        $_real_amount	= Input::get('real_amount');
        $_fee_amount	= Input::get('fee_amount');
        
        $_ret = FundInterface::fund($_total_amount);
        if ($_ret === false)
        {
        	App::abort(404);	// 这么处理是否合理，还要看到时候的接口
        }
        
		$_ret = UserService::add_fund($_total_amount, 
										$_real_amount, $_fee_amount);
		if ($_ret === Ret::RET_FAILED)
		{
			App::abort(404);	// 这么处理是否合理，还要看到时候的接口
		}        

        return Redirect::to('/home');
    }

    public function getDepositBank($amount = 0, $currency = NULL)
    {

        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        // 获取可选银行
        $bank_result = array();

        $bank_ids = autoSelectBank($currency, $amount);
        foreach ($bank_ids as $b_id) 
        {
            $bank_obj = Bank::find($b_id);
            $bank_result[] = array('value' => $bank_obj->id, 'text' => $bank_obj->bank);
        }

        if (empty($bank_result))
        {
            $bank_result[] = array('value' => -1, 'text' => 'Anying Bank');   
        }

        return $this->push('ok', array('data' => $bank_result)); 

    } 
}