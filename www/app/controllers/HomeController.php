<?php namespace Controllers;

use View;
use Input;
use Redirect;
use Breadcrumb;


use Tx;

/**
 *  网站前端页面控制器
 */

class HomeController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.basic';

	/**
	 * 显示首页
	 *
	 * @return void;
	 */
	public function index()
	{     
    	$this->layout->content = View::make('index', $this->data);
	}

    /**
     * 显示首页 personal
     *
     * @return void;
     */
    public function getPersonal()
    {
        $this->layout->content = View::make('personal', $this->data);
    }

    /**
     * 显示首页 business
     *
     * @return void;
     */
    public function getBusiness()
    {
        $this->layout->content = View::make('business', $this->data);
    }

    /**
     * 显示首页 news
     *
     * @return void;
     */
    public function getNews()
    {
        $this->layout->content = View::make('news', $this->data);
    }

	/**
	 * 锁定汇率
	 *
	 * @return void;
	 */
	public function postLock()
	{
    	// 锁定换汇金额
        Exchange::amount(Input::get('amount_have'));

        // 是否已登录
        if (is_login())
        {
            return Redirect::to('lockrate/money');
        }
        return Redirect::to('login/login-or-signup');
	}

	/**
     * 刷新锁定汇率
     *
     * @return json
     */
    public function getRefresh()
    {  
        $rep = Exchange::refresh();

        return $this->push('ok', $rep);
    }

    /**
     * comming soon
     *
     * @return void
     */
    public function getComingSoon()
    { 
		return View::make('comingsoon');
    }

    /**
     * 显示privacy
     *
     * @return void;
     */
    public function getPrivacy()
    {
        $this->layout = View::make('layouts.business');

        $this->layout->content = View::make('privacy', $this->data);
    }

    /**
     * 显示legal
     *
     * @return void;
     */
    public function getLegal()
    {
        $this->layout = View::make('layouts.business');

        $this->layout->content = View::make('legal', $this->data);
    }
    
    public function getInvestPawnList()
    {
    	Breadcrumb::map(array('我要投资' => url('/invest')))->append('抵押贷投资列表');
    	
    	$_txs = Tx::debtlist_txs($this->DEFAULT_PAGINATION_CNT);
    	
    	$this->data['txs'] 			= $_txs;

    	$this->layout->content = View::make('invest.pawn.index', $this->data);    	
    }
}