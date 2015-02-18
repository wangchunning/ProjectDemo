<?php namespace Controllers\Register;

use Redirect;
use Hash;
use Auth;
use User;
use Controllers\BaseController;

/**
 *  新用户邮件验证控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class ConfirmController extends BaseController {

    /**
     * User model
     *
     * @var WeXchange\Model\User
     */
    protected $user;

    /**
     * Inject user model to the current container
     *
     * @param  WeXchange\Model\User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Confirm an activation via the link in email
     *
     * @param  int      id of the user
     * @param  string   Validation token
     * @return Redirect
     */
    public function index($uid, $token)
    {
         $cu = $this->user->findOrFail($uid);

         // 验证通过?
        if (Hash::check($token, $cu->verify->getAttribute('email_verify_token')))
        {
            $cu->verify->email_verified = 1;
            $cu->verify->email_verify_token = NULL;
            $cu->verify->save();
        }        

        return Redirect::to('home');
    }
}