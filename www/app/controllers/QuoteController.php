<?php namespace Controllers;

use Exchange;
use Input;
use Auth;
use Request;
use App;
use DB;
use Rate;
use URL;

/**
 *  汇率控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

class QuoteController extends BaseController {

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
	 * 获取汇率数据
	 *
     * @param $type
	 * @return Response;
	 */
	public function getIndex($type = 'user')
	{
		// 非ajax请求？
		if ( ! Request::ajax()) 
        {
            App::abort(404, 'Page not found');
        }
		
    	if ( ! $currency = Input::get('symbols')) 
        {
            return $this->push('error', array('msg' => '没有选择币种!'));
        }

        $userType = 'market';

        if ($type == 'user') 
        {
            // 用户未登录
            if ( ! is_login()) 
            {
                return $this->push('error', array('msg' => '您没有登录!'));
            }
            $user = real_user();
            $userType = $user->profile->vip_type;
        }
    	
    	$data = Exchange::query($currency, $userType);
    	echo json_encode(array('Ticks' => $data));
        exit;
	}

    /**
     * 获取汇率历史记录
     *
     * @return Response
     */
    public function getHistory()
    {
        $symbol = Input::get('symbol');

        $output = Exchange::history($symbol);

        return $this->push('ok', $output);
    }

    /**
     * 获取换汇数据
     * 
     * @return json
     */
    public function getConvert()
    {
        $userType = 'customer';
        // 用户登录
        if (is_login()) 
        {
            // 确认获取的用户汇率
            //$user = Auth::member()->user();
            $user = real_user();
            $userType = $user->profile->vip_type;
        }

        $need_lockrate = 1;
        if (Input::has('need_lockrate'))
        {
            $need_lockrate = Input::get('need_lockrate');
        }
        $currency = Input::get('symbol');
        $data = Exchange::query($currency, $userType);

        // 锁定当前汇率
        if ($need_lockrate)
        {
            Exchange::lock($currency, $data[0]['Bid'], $data[0]['RateForShow']);
        }
        
        echo json_encode($data);exit;
        exit;
    }

    /**
     * 选择 Local 币种，获取可以对应兑换的货币列表
     *
     * @return void;
     */
    public function getForeignCurrencies()
    {
        $output = '';

        $rate = new Rate;

        foreach ($rate->getForeignCurrencies(Input::get('currency')) as $foreign)
        {
            $output .= sprintf('<option data-iconurl="%s.png" value="%s">%s - %s</option>', URL::asset('assets/img/flags/' . $foreign), $foreign, $foreign, currencyName($foreign));
        }

        echo $output;
    }

}