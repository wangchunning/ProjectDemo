<?php namespace Controllers\Repayment;

use View;
use Carbon;
use App;

use Controllers\AuthController;
use Breadcrumb;
use Input;
use Validator;
use Redirect;
use Request;

use User;
use DebtTx;
use CreditLevel;
use Repayment;
/**
 *  
 *
 */
class RepaymentController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

	public function postCalcRepayment()
	{
		if (!Request::ajax()) 
        {
            App::abort(404);
        }
        
        $_validation = Validator::make(Input::all(), array(
        	'amount'	=> 'required|numeric|min:1',
        	'period'    => 'required|numeric|min:1',
        	'rate' 		=> 'required|numeric',
        	'uid'       => 'required|numeric',
        	'product'	=> 'required'
        ));
        if ($_validation->fails())
        {
        	return $this->push('error', array('msg' => $_validation->messages()->first()));
        }
        
        $_ret = Repayment::calc_repayment(Input::get('amount'), 
        									Input::get('period'), 
        									Input::get('rate'), 
        									Input::get('uid'), 
        									Input::get('product'));

        
        return $this->push('ok', array('data' => $_ret));
	}
}