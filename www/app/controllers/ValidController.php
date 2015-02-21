<?php namespace Controllers;

use Input;
use Validator;
use Response;


/**
 *  表单验证规则控制器
 *  
 *  主要用于前端页面通过 ajax 验证表单，用户不必登录
 *  
 *  因为前端使用了 bootstrapValidator，要求返回 json 必须包含 valid 字段，
 *  所以这里没有使用 Ret 类
 *
 */
Class ValidController extends BaseController {

    /**
     * 用户名必须唯一
     */
    public function postUniqueUserName()
    {   
        $_messages = array(
            'user_name.unique'     => '用户名已被占用'
        );
        
        $_validator = Validator::make(Input::all(), array(
            'user_name'         => 'required|unique:users'
        ), $_messages);
        

		return Response::json(array('valid' => !$_validator->fails()));
    }

    /**
     * 验证是否有效国家
     *
     * @return string
     */
    public function postCountry()
    {
        $_country = ucfirst(Input::get('country'));

        $_countries = countries();
        
        return Response::json(array('valid' => isset($_countries[$_country])));
    }
   
}