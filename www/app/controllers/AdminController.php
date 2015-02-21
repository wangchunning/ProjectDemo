<?php namespace Controllers;

/**
 *  后台管理父类
 *
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