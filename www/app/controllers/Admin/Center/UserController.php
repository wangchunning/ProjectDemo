<?php namespace Controllers\Admin;

use View;
use Validator;
use Redirect;
use User;
use UserInvite;
use Role;
use Activity;
use Auth;
use Input;
use Hash;
use Mail;
use Controllers\AdminController;

/**
 *  后台用户管理控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class UserController extends AdminController {

    /**
     * User model
     *
     * @var WeXchange\Model\User
     */
    protected $user;

    /**
     * UserInvite model
     *
     * @var WeXchange\Model\UserInvite
     */
    protected $invite;

    public function __construct(User $user, UserInvite $invite)
    {
        parent::__construct();

        $this->user = $user;
        $this->invite = $invite;
    }

    /**
     * 显示后台用户列表
     *
     * @param  string   $status 
     * @return void
     */
    public function getIndex($status = NULL)
    {
        // 权限检查
        check_perm('view_user');

        $managers = $this->user->where('type', '=', 'manager')->paginate(6);

        $invites = $this->invite->paginate(6);

        $this->layout->content = View::make('admin.center.manage.index')->with(array('managers' => $managers, 'invites' => $invites));
    }

    /**
     * 编辑后台用户信息
     *
     * @param  int   $uid 
     * @return void
     */
    public function getEdit($uid)
    {
        // 权限检查
        check_perms('view_user,edit_user');

        if ( ! $manager = $this->user->find($uid)) 
        {
            return Redirect::back();
        }

        $roles = Role::all()->toArray();

        $user_role = array();
        foreach ($manager->roles as $r) 
        {
            $user_role[] = $r->id;
        }

        $this->layout->content = View::make('admin.center.manage.edit')->with(array('manager' => $manager, 'roles' => $roles, 'user_role' => $user_role));
    }

    /**
     * 保存后台用户信息
     *
     * @param  int   $uid 
     * @return void
     */
    public function postEdit($uid)
    {
        // 权限检查
        check_perms('view_user,edit_user');

        if ( ! $manager = $this->user->find($uid)) 
        {
            return Redirect::back();
        }

        $messages = array(
            'email.unique'           => 'The email has already been taken, please contact support@wexchange.com',
        );

        $validator = Validator::make(Input::all(), array(
            'email'                  => "required|email|unique:users,email,{$uid},uid",
            'first_name'             => 'required',
            'last_name'              => 'required'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::to(sprintf('admin/users/edit/%s',$uid))->withInput()->withErrors($validator);
        }

        $user_role = array();
        foreach ($manager->roles as $r) 
        {
            $user_role[] = $r->id;
        }
        $manager->roles()->detach($user_role);

        $manager->email = Input::get('email');
        $manager->first_name = Input::get('first_name');
        $manager->middle_name = Input::get('middle_name');
        $manager->last_name = Input::get('last_name');
        $manager->save();
        $manager->roles()->attach(Input::get('roles'));

        // add log
        Activity::log(array(
            'obj_id'   => $uid,
            'activity_type' => 'Users',
            'action'        => 'Updated',
            'description' => 'Updated ' . $manager->email . ' information'
        ));

        return Redirect::to('admin/users');
    }

    /**
     * 软删除后台用户
     *
     * @param  int   $uid 
     * @return Response
     */
    public function getRemove($uid)
    {
        // 权限检查
        check_perms('view_user,remove_user');

        if ( ! $manager = $this->user->find($uid)) 
        {
            return $this->push('error', array('msg' => 'The user is not exists!'));
        }

        $manager->delete();

        // add log
        Activity::log(array(
            'obj_id'   => $uid,
            'activity_type' => 'Users',
            'action'        => 'Delete',
            'description' => 'Delete manager ' . $manager->full_name
        ));

        return $this->push('ok');
    }

    /**
     * 显示邀请用户页面
     *
     * @return void
     */
    public function getInvite()
    {
        // 权限检查
        check_perms('view_user,invite_user');

        $roles = Role::all()->toArray();

        $this->layout->content = View::make('admin.center.manage.invite')->with(array('roles' => $roles));
    }

    /**
     * 邀请用户信息处理
     *
     * @return Response
     */
    public function postInvite()
    {
        // 权限检查
        check_perms('view_user,invite_user');

        $validator = Validator::make(Input::all(), array(
            'email' => 'required|email|unique:users|unique:user_invite',
            'roles' => 'required'
        ));

        if ($validator->fails())
        {            
            return $this->push('error', array('msg' => $validator->messages()->first()));
        } 

        $invite = new UserInvite;
        $email = Input::get('email');
        $invite->invite_token = hash_hmac('sha1', str_shuffle(sha1($email.microtime(true))), str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));
        $invite->email = $email;
        $invite->roles = Input::get('roles');
        $invite->expires = date('Y-m-d H:i:s', time() + 48 * 3600);
        $invite->save();

        $manager = Auth::admin()->user();

        $data = array(
            'url'       => url('admin/users/signup', array($invite->id, $invite->invite_token)),
            'manager'   => $manager->full_name,
            'roles'     => id_to_string($invite->roles)
        );

        Mail::queue('emails.user_invite', $data, function($message) use ($email)
        {
            $message->to($email)->subject('User Invitation');
        });

        // add log
        Activity::log(array(
            'obj_id'   => $invite->id,
            'activity_type' => 'Users',
            'action'        => 'Invite',
            'description' => 'Invited ' . $invite->email . ' to help manage ' . $data['roles'] . ' for Wexchange'
        ));

        return $this->push('ok');
    }

    /**
     * 删除邀请用户
     *
     * @param  int   $id 
     * @return Response
     */
    public function getInviteRemove($id)
    {
        // 权限检查
        check_perms('view_user,invite_user');

        if ( ! $invite = UserInvite::find($id)) 
        {
            return $this->push('error', array('msg' => 'The user is not exists!'));
        }

        $invite->delete();

        // add log
        Activity::log(array(
            'obj_id'   => $id,
            'activity_type' => 'Users',
            'action'        => 'Delete',
            'description' => 'Delete invites ' . $invite->email
        ));

        return $this->push('ok');
    }

    /**
     * 重发邀请邮件
     * 
     * @param  int  $id
     * @return Response
     */
    public function getResend($id)
    {
        // 权限检查
        check_perms('view_user,invite_user');

        $invite = new UserInvite;
        $manager = Auth::admin()->user();
        $detail = $invite->find($id);

        if ( ! $detail) return $this->push('error', array('msg' => 'The user is not exists!'));

        $email = $detail->email;

        $data = array(
            'url'       => url('admin/users/signup', array($detail->id, $detail->invite_token)),
            'manager'   => $manager->full_name,
            'roles'     => id_to_string($detail->roles)
        );

        $detail->expires = date('Y-m-d H:i:s', time() + 48 * 3600);
        $detail->save();

        Mail::queue('emails.user_invite', $data, function($message) use ($email)
        {
            $message->to($email)->subject('User Invitation');
        });

        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'activity_type' => 'Users',
            'action'        => 'Invite',
            'description' => 'Resend invite email to' . $email
        ));

        return $this->push('ok', array('expires' => $detail->expires));
    }
}
