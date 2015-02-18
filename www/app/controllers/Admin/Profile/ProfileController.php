<?php namespace Controllers\Admin;

use View;
use Validator;
use Redirect;
use Auth;
use Input;
use Hash;
use Activity;
use Controllers\AdminController;

/**
 *  后台用户管理控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class ProfileController extends AdminController {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 显示用户信息
     *
     * @return void
     */
    public function getIndex()
    {
        $manager = Auth::admin()->user();

        $this->layout->content = View::make('admin.profile.index')->with(array('manager' => $manager));
    }

    /**
     * 修改管理员状态
     *
     * @param  string  $status
     * @return Response
     */
    public function getChangeStatus($status)
    {
        $manager = Auth::admin()->user();
        $manager->manager_status = $status;
        $manager->save();

        // add log
        Activity::log(array(
            'obj_id'   => $manager->uid,
            'activity_type' => 'Users',
            'action'        => 'Updated',
            'description' => 'Change status to ' . $manager->manager_status
        ));
        return $this->push('ok');
    }

    /**
     * 编辑后台用户信息
     *
     * @return void
     */
    public function getEdit()
    {
        $manager = Auth::admin()->user();

        $this->layout->content = View::make('admin.profile.edit')->with(array('manager' => $manager));
    }

    /**
     * 保存后台用户信息
     *
     * @return void
     */
    public function postEdit()
    {
        $manager = Auth::admin()->user();

        $messages = array(
            'email.unique'           => 'The email has already been taken, please contact support@wexchange.com',
            'password:min'           => 'Sorry, Your password is too short!'
        );

        $validator = Validator::make(Input::all(), array(
            'email'                  => "required|email|unique:users,email,{$manager->uid},uid",
            'password'               => 'min:8|confirmed',
            'first_name'             => 'required',
            'last_name'              => 'required'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::to('admin/profile/edit')->withInput()->withErrors($validator);
        }

        // 修改密码?
        if ($password = Input::get('password'))
        {
            $manager->password = Hash::make($password);
        }

        $manager->email = Input::get('email');
        $manager->first_name = Input::get('first_name');
        $manager->middle_name = Input::get('middle_name');
        $manager->last_name = Input::get('last_name');
        $manager->timezone = Input::get('timezone');
        $manager->save();

        // add log
        Activity::log(array(
            'obj_id'   => $manager->uid,
            'activity_type' => 'Users',
            'action'        => 'Updated',
            'description' => 'Updated information'
        ));

        return Redirect::to('admin/profile');
    }
}
