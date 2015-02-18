<?php namespace Tt\Model;


class UserLoginLog extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_login_log';
  
    const LOGIN_FROM_WEB 	= 0;
    const LOGIN_FROM_WECHAT = 1;
    const LOGIN_FROM_APP 	= 2;
    const LOGIN_FROM_ADMIN 	= 3;
    
}