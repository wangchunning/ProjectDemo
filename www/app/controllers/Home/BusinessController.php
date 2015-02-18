<?php namespace Controllers\Home;

use View;
use BalanceHistory;
use App;
use Controllers\AuthController;
use Breadcrumb;
use Session;
use Validator;
use Input;
use UserMapping;
use Redirect;
use Response;
use Mail;
use Queue;
use User;
use DB;
use BusinessInvite;



/**
 * BusinessController
 *     - v0.9 开始，business 账户引入了子账户的概念。即，一个business账户可以拥有多个子账户
 *     - 该控制器用于business及其子账户的操作
 *
 *                              2014-03-20
 */
class BusinessController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     * RemoveMember
     *     - 将用户从公司成员列表中移除
     *     - 结果：成员个人账户仍可用，但无法再管理Business账户
     *
     * @var muid - 要移除的用户uid
     * @var buid - business用户uid
     */
    public function getRemoveMember($buid, $muid)
    {

        $business_uid   = $buid;
        $member_uid     = $muid;

        UserMapping::where('parent_id', $business_uid)
                    ->where('user_id', $muid)
                    ->delete();

        return Redirect::back();
    }  

   /**
     * 邀请用户信息处理
     *
     * @return Response
     */
    public function postInviteBusinessMember($uid)
    {
        // 权限检查
        //check_perms('view_user,invite_user');

        $validator = Validator::make(Input::all(), array(
            'emails' => 'required',
        ));

        if ($validator->fails())
        {            
            return $this->push('error', array('msg' => $validator->messages()->first()));
        } 

        $parent = User::find($uid);

        $emails = explode(';', Input::get('emails'));
        foreach ($emails as $email)
        {
            $email = trim($email);
            if (empty($email))
            {
                continue;
            }
            /**
             * 验证 email 
             */
            $validator = Validator::make(
                array('email' => $email ),
                array('email' => 'email')
            );
            if ($validator->fails())
            {            
                $msg_err = $email . '. ' . $validator->messages()->first();
                return $this->push('error', array('msg' => $msg_err));
            }

            /**
             * 检查是否该 email 已经是子账户，
             * 这里保证一个 business member 只有一个父账户
             */
            $invitee = User::join('user_mapping', 
                                    'users.uid', '=', 
                                    'user_mapping.user_id')
                        ->where('users.email', $email)
                        ->first();
            if (!empty($invitee))
            {
                $msg_err = $email . ' 已经是公司成员';
                return $this->push('error', array('msg' => $msg_err));
            }
            /**
             * 被邀请人必须是注册过的账号
             */
            $invitee = User::where('email', $email)->first(); 
            if (empty($invitee))
            {
                $msg_err = $email . ' 还没有注册成 Anying 用户';
                return $this->push('error', array('msg' => $msg_err));
            }
            /**
             * 被邀请人不能是管理员
             */
            if ($invitee->type == 'manager')
            {
                $msg_err = $email . ' 是管理员账号';
                return $this->push('error', array('msg' => $msg_err));
            }            

            /**
             * 发送邀请
             */  
            $invite = new BusinessInvite;
            $invite->invite_token = hash_hmac('sha1', 
                                            str_shuffle(sha1($email.microtime(true))), 
                                            str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));
            $invite->invitor_id = $uid;             // 邀请人
            $invite->invitee_id = $invitee->uid;    // 被邀请人
            $invite->email      = $email;           // 被邀请人 email
            $invite->expires    = date('Y-m-d H:i:s', time() + 48 * 3600);
            $invite->save();

            $data = array(
                'inviter'   => $parent->full_name,
                'company'   => $parent->business->business_name,
                'url'       => url('business-member-checkin', array($invite->id, $invite->invite_token))
            );

            Mail::send('emails.business_invite', $data, function($message) use ($email)
            {
                $message->to($email)->subject('公司成员邀请 - Anying');
            });
        }

        return $this->push('ok');
    }

}