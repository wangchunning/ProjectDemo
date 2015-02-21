<?php namespace Controllers;

use View;


/**
 *  首页控制器，无需登录
 *  
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
    	return View::make('index', $this->data);
	}

    /**
     * about us
     *
     * @return void;
     */
    public function getAbout()
    {
        return View::make('about', $this->data);
    }

    /**
     * contract us
     *
     * @return void;
     */
    public function getContract()
    {
    	return View::make('contract', $this->data);
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

         return View::make('privacy', $this->data);
    }

    /**
     * 显示legal
     *
     * @return void;
     */
    public function getLegal()
    {
        $this->layout = View::make('layouts.business');

        return View::make('legal', $this->data);
    }

}