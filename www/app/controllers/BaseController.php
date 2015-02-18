<?php namespace Controllers;

use View;
use Session;
use Response;

/**
 *  Parent controller for all pages
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

    /**
     * Helper function to help the Json data pushed to the client
     *
     * @param  string    Status of the process: {'ok', 'error'}
     * @param  mixed     Data to be pushed to the client.
     * @return \Response Encoded json data string
     */
    protected function push($status, $data = array())
    {
        return Response::json(compact('status', 'data'));
    }    
}