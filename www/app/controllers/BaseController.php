<?php namespace Controllers;

use View;

/**
 *  前后台所有控制器父类
 *
 *  It should be extended by almost all child controllers, which require 
 *  {@see http://laravel.com/docs/templates layout} and other common 
 *  functionalites support.
 *
 *
 */
class BaseController extends \Controller {

	protected $DEFAULT_PAGINATION_CNT = 15;
	/**
     * Data pushed to the client
     *
     * @var array
     */
    protected $data = array();

    /**
     * Setup the shared procedures
     */
    public function __construct()
    {
        // Make sure the CSRF token is attached to the reponse automatically
        $this->data['_token'] = Session::token();
        
        /**
         * 清洗用户输入数据，去掉空格等
         */
        Input::merge(array_map('trim', Input::all()));
    }    

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
   
}