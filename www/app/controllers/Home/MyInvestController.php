<?php namespace Controllers\Home;

use View;
use Auth;
use Carbon;
use App;
use Request;
use Response;
use Controllers\AuthController;
use Breadcrumb;
use Session;
use Input;

use User;
use Tx;
use DebtTx;
use BalanceHistory;
use UserService;
/**
 *  我的投资
 *
 */
class MyInvestController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     * 显示首页页面
     *
     * @return void
     */
    public function getIndex()
    {
        Breadcrumb::map(array('我的账户' => url('/home')))->append('我的投资');
        
        $_invests = UserService::my_invests($this->DEFAULT_PAGINATION_CNT);
		
		$this->data['invests'] 			= $_invests;
		
		$this->layout->content = View::make('home.myinvest.index', $this->data);
    }  

}
