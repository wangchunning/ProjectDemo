<?php namespace Controllers;

use Response;

/**
 *  公共信息查询控制器
 *   
 *
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

class QueryController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        // 未登录
        if ( ! is_login() AND ! is_login('admin')) 
        {
            return $this->push('error', array('msg' => '您没有登录!'));
        }
    }

    /**
     * Json输出，所有国家名称
     *
     */
    public function getCountries()
    {   
        return Response::json(countries(true));
    }

}