<?php namespace Controllers;

use View;

/**
 *  Parent controller for all pages
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
Class AuthController extends BaseController {


    /**
     * default entries for 'show more' feature
     */
    const DEFAULT_SHOW_MORE_ENTRIES = 50;

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