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
/**
 *  my transactions history
 *
 */
class MyDebtController extends AuthController {

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
        Breadcrumb::map(array('我的账户' => url('/home')))->append('我的借款');

        //$_user	= login_user();
        
        $_start_date	= Input::get('start_date', '');
        $_end_date		= Input::get('end_date', '');
        $_search		= Input::get('search', '');
        $_status		= Input::get('status', '');
        
        if (empty($_start_date))
        {
        	$_start_date = Carbon::now()->subYears(4)->toDateString();
        }
        
        $_txs = Tx::mydebt_txs($_start_date, $_end_date, $_search, $_status, $this->DEFAULT_PAGINATION_CNT);
		
		$this->data['status'] 		= $_status;
		$this->data['status_arr'] 	= array_merge(array('' => '全部状态'), Tx::status_arr());
		$this->data['start_date'] 	= $_start_date;
		$this->data['end_date'] 	= $_end_date;
		$this->data['search'] 		= $_search;
		$this->data['txs'] 			= $_txs;
		$this->data['search_placeholder'] 	= '标题';
		
		$this->layout->content = View::make('home.mydebt.index', $this->data);
    }  

}
