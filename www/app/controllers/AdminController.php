<?php namespace Controllers;

use View;

/**
 *  后台管理控制器父类
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
Class AdminController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.admin';

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

        $this->beforeFilter('auth.admin');

        $this->beforeFilter('session_timeout');       
    }
}