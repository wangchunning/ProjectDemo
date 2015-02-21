<?php namespace Controllers;

use View;

/**
 *  前台页面父类
 *
 */
Class AuthController extends BaseController {


    /**
     * default entries for 'show more' feature
     */
    //const DEFAULT_SHOW_MORE_ENTRIES = 50;

    /**
     * Setup the shared authentication procedures
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->beforeFilter('auth');

        $this->beforeFilter('session_timeout');

        $this->beforeFilter('verifyStepCheck');
    }
}