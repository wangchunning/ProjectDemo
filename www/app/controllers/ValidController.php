<?php namespace Controllers;

use Input;
use Validator;
use User;
use Response;
use SwiftCode;
use Request;
use App;
/**
 *  表单验证控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
Class ValidController extends BaseController {

    /**
     * 验证用户名
     */
    public function postUniqueUserName()
    {   
        $messages = array(
            'user_name.unique'     => '用户名已被占用'
        );
        $validator = Validator::make(Input::all(), array(
            'user_name'         => 'required|unique:users'
        ), $messages);
        
        if ($validator->fails())
        {
            echo json_encode(array('valid' => false));
            exit;
        }
        echo json_encode(array('valid' => true));
        exit;
    }
    /**
     * 验证用户名
     */
    public function postUniqueIdNumber()
    {
    	$messages = array(
    			'id_number.unique'     => '用户名已被占用'
    	);
    	$validator = Validator::make(Input::all(), array(
    			'id_number'         => 'required|unique:user_profile'
    	), $messages);
    
    	if ($validator->fails())
    	{
    		echo json_encode(array('valid' => false));
    		exit;
    	}
    	echo json_encode(array('valid' => true));
    	exit;
    }
    /**
     * 验证swift_code有效性
     *
     * @return Response;
     */
    public function getSwiftCode()
    {
        $validator = Validator::make(array('swift_code' => Input::get('fieldValue')), array(
            'swift_code'         => 'exists:swift_code'
        ));
        if ( ! Input::get('fieldValue'))
        {
            echo json_encode(array(Input::get('fieldId'), false));
            exit;
        }
        if ($validator->fails())
        {
            echo json_encode(array(Input::get('fieldId'), false));
            exit;
        }
        echo json_encode(array(Input::get('fieldId'), true));
        exit;
    }

    public function getSwiftcodeBank()
    {
        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        $swift_code_obj = SwiftCode::where('swift_code', Input::get('swiftcode'))->first();
        if(isset($swift_code_obj->bank))
        {
            return $swift_code_obj->toJson();
        }

        return json_encode(array());
    }

    /**
     * 判断邮箱是否在wexchange注册
     *
     * @return bool
     */
    public function getIfWexchange()
    {
        $validator = Validator::make(array('email' => Input::get('fieldValue')), array(
            'email'         => 'email'
        ));
        
        if ($validator->fails())
        {
            echo json_encode(array(Input::get('fieldId'), false, $validator->messages()->first('email')));
            exit;
        }
        if ($user = User::where('email', '=', Input::get('fieldValue'))->where('type', '=', 'user')->first()) 
        {
            echo json_encode(array(Input::get('fieldId'), true, '用户已存在!'));
            exit;
        }

        echo json_encode(array(Input::get('fieldId'), true));
        exit;      
    }

    /**
     * 验证是否有效国家
     *
     * @return string
     */
    public function getCountry()
    {
        $country = ucfirst(Input::get('fieldValue'));

        $countries = countries();

        return Response::json(array(Input::get('fieldId'), isset($countries[$country])));
    }

    /**
     * 验证是否有效国家
     *
     * @return string
     */
    public function postCountry()
    {
        $country = ucfirst(Input::get('fieldValue'));

        $countries = countries();

        return Response::json(array(Input::get('fieldId'), isset($countries[$country])));
    }    
}