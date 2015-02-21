<?php namespace Controllers;

use Request;
use Administrator;
use Ret;

/**
 *  公共信息查询控制器，返回 json
 *  	1) 全部为 ajax 请求
 *  	2) 只针对登录用户可用
 *   
 */
class QueryController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        /**
         * 只针对 ajax 请求
         */
        if (!Request:: ajax())
        {
        	App::abort(404, 'Page not found');
        }
        
        /**
         * 只针对登录用户可用
         */
        if (!is_login() && !is_login(Administrator::LABEL)) 
        {
        	return Ret::json(Ret::RET_FAILED, array(), 
        						Ret::ERR_LOGIN_REQUIRED, '尚未登录，请重新登录');
        }
    }

    /**
     * Json输出，所有国家名称
     */
    public function getCountries()
    {   
    	return Ret::json(Ret::RET_SUCC, countries(true));
    }

}