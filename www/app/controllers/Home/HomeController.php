<?php namespace Controllers\Home;

use View;
use Auth;
use Carbon;
use Exchange;
use BalanceHistory;
use App;
use Controllers\AuthController;
use Breadcrumb;
use Session;
use Tx;
use UserService;
/**
 *  用户后台首页控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class HomeController extends AuthController {

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
    	//Breadcrumb::map(array('首页' => url('/'), '个人信息' => url('profile')))->append('编辑个人信息');
    	
		$_user = login_user();
		
        $this->data['user'] 	= $_user;
        $this->data['balance'] 	= $_user->balance();

        $_start_date = Carbon::now()->subYears(4)->toDateString();
        $_limit		= 5;
        $_txs = Tx::mydebt_txs($_start_date, '', '', '', $_limit); // 显示最近的5条，2年内
        
        $_invests = UserService::my_invests(0, $_limit);
        
        
        $this->data['invests'] 			= $_invests;
        $this->data['txs'] = $_txs;
        $this->data['receipts'] = array();
        $this->layout->content = View::make('home.index', $this->data);         
    }  

    /**
     * Balance 流水记录
     *
     * @param  string
     * @return void
     */
    public function getBalance()
    {
    	// set breadcrumb
    	Breadcrumb::map(array('我的账户' => url('/home')))->append('余额流水');
    	
        $_user = login_user();
		$_balance	= $_user->balance();
		
		$this->data['balance'] = $_balance;
		
        $this->data['histories'] = BalanceHistory::where('uid', '=', $_user->uid)
                                    ->orderBy('created_at', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->paginate(20);                                    
                                    


        $this->layout->content = View::make('home.balance.index', $this->data);
    }  

    /**
     * 显示收据页面
     *
     * @param  string 
     * @return void
     */
    public function getReceipt($receipt_id = '')
    {
        // 获取上步闪存的收据 id
        if ( ! $receipt_id)
        {
            App::abort(404, 'Page not found');          
        }

        $user = real_user();

        // 获取收据信息
        if ( ! $receipt_obj = $user->receipts()->where('id', '=', $receipt_id)->get()->first())
        {
            App::abort(404, 'Page not found');
        }
        $receipt = $receipt_obj->toArray();

        switch ($receipt['type'])
        {
            case 'Deposit':
                $view = 'lockrate.fund.receipt';
                break;

            case 'Withdraw':
                $view = 'lockrate.withdraw.receipt';
                break;
            default:
                $view = 'lockrate.lock.receipt';
                break;
        }

        // 反序化收据详情
        $data = unserialize($receipt['data']);

        $tran_complete = false;
        if (Session::has('breadcrumb')) 
        {
            $breadcrumb = unserialize(Session::get('breadcrumb'));
            if (isset($breadcrumb['lockrate'])) 
            {
                // set breadcrumb
                Breadcrumb::map($breadcrumb['lockrate'])->append('交易完成');
                // 交易最后一步标识
                $tran_complete = true;
            }
            elseif (isset($breadcrumb['tx_history'])) 
            {
                // set breadcrumb
                Breadcrumb::map($breadcrumb['tx_history'])->append('收据 ' . $receipt_id);
            }
        }
        else
        {
            // set breadcrumb
            Breadcrumb::map(array('我的账户' => url('home')))->append('收据详情');
        }
         
        if ($receipt['type'] == 'Withdraw')
        {
        	$this->layout->content = View::make($view)
        			->with('data', $data)
        			->with('receipt', $receipt)
        			->with('receipt_obj', $receipt_obj)
        			->with('currency', isset($data[0]['currency']) ? $data[0]['currency'] : $data['currency'])
        			->with('tran_complete', $tran_complete);
        	return;
       	
        }

        $data['receipt'] = $receipt;
        $data['receipt_obj'] = $receipt_obj; 
        $data['tran_complete'] = $tran_complete;

        $this->layout->content = View::make($view, $data); 
    } 
}