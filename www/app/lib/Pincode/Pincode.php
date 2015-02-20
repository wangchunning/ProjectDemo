<?php namespace Lib\Pincode;

use Queue;
use Sms;
use Session;
use App;

/**
 *  用于处理手机验证码
 *
 */

class Pincode {


    /**
     * 创建一个验证码
     * 
     * @param  bool  是否重新创建
     * @return void
     */
    public function create($anew = FALSE)
    {
        // 不重建且验证码已存在？
        if ( ! $anew AND Session::has('sms_pin'))
        {
            return;
        }

        // 生成4位数的手机验证码
        Session::put('sms_pin', str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));
    }

    /**
     * 发送验证码到手机
     *
     * @param  string 
     * @param  string
     * @return void
     */
    public function send($phoneNumber, $message)
    {
        if ( ! $phoneNumber OR ! $message)
        {
            return;
        }

        // 信息内容
        $message = sprintf($message, Session::get('sms_pin'));

        // 短信结构
        $sms = array(
            'to'   => $phoneNumber,
            'text' => $message
        );

        // 推到队列发送
        /*Queue::push(function($job) use ($sms)
        {
            Sms::send($sms);
            
            $job->delete();
        });*/        
    }

    /**
     * 手机验证码是否正确
     *
     * @param  string
     * @return bool
     */
    public function validate($pincode)
    {   
        if (Session::has('sms_pin') AND ! $pincode)
        {
            return FALSE;
        }

        if ($pincode === Session::get('sms_pin'))
        {
            Session::forget('sms_pin');

            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * 清除验证码
     * 
     * @return void
     */
    public function clear()
    {
        Session::forget('sms_pin');
    }
}