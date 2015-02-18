<?php namespace Controllers\Admin;

use View;
use Auth;
use Role;
use Activity;
use Validator;
use Input;
use Controllers\AdminController;
use Permission;
use Redirect;

use CreditLevel;
/**
 *  后台设置控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class SettingController extends AdminController {

    /**
     * The manager info
     *
     * @var object
     */
    protected $manager;

    public function __construct()
    {
        parent::__construct();

        $this->manager = Auth::admin()->user();
    }

    /**
     * 显示setting主页
     *
     * @return void
     */
    public function getIndex()
    {
        $this->layout->content = View::make('admin.setting.index')->with(array('manager' => $this->manager));
    }

    /**
     * Roles setting
     *
     * @return void
     */
    public function getRoles()
    {
        // 权限检查
        check_perm('view_role');

        $roles = Role::paginate(10);

        $this->layout->content = View::make('admin.setting.role.role')->with(array('manager' => $this->manager, 'roles' => $roles));
    }

    /**
     * Roles Add
     *
     * @return Response
     */
    public function postRoleAdd()
    {
        // 权限检查
        check_perms('view_role,add_role');

        $validator = Validator::make(Input::all(), array(
            'name' => 'required|unique:roles',
            'description' => 'required'
        ));

        if ($validator->fails())
        {            
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }
        $role = new Role;
        $role->name = Input::get('name');
        $role->description = Input::get('description');
        $role->save();

        // add log
        Activity::log(array(
            'obj_id'   => $role->id,
            'activity_type' => 'System',
            'action'        => 'Add',
            'description' => 'Add New Role ' . $role->name
        ));

        return $this->push('ok', array('action' => 'add', 'role_id' => $role->id));
    }

    /**
     * Roles Edit
     *
     * @return Response
     */
    public function postRoleEdit()
    {
        // 权限检查
        check_perms('view_role,edit_role');

        $id = Input::get('role_id');
        $messages = array(
            'role_id.required'    => 'The item is not exists!'
        );

        $validator = Validator::make(Input::all(), array(
            'role_id' => 'required',
            'name' => "required|unique:roles,name,{$id},id",
            'description' => 'required'
        ));

        if ($validator->fails())
        {            
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }
        $role = Role::find($id);
        if ( ! $role) 
        {
            return $this->push('error', array('msg' => 'The item is not exists!'));
        }
        $role->name = Input::get('name');
        $role->description = Input::get('description');
        $role->save();

        // add log
        Activity::log(array(
            'obj_id'   => $role->id,
            'activity_type' => 'System',
            'action'        => 'Updated',
            'description' => 'Updated Role ' . $role->name
        ));

        return $this->push('ok', array('action' => 'edit', 'role_id' => $role->id));
    }

    /**
     * Roles Remove
     *
     * @param  int   $id 
     * @return Response
     */
    public function getRoleRemove($id)
    {
        // 权限检查
        check_perms('view_role,remove_role');

        $role = Role::find($id);
        if ( ! $role) 
        {
            return $this->push('error', array('msg' => 'The item is not exists!'));
        }
        $data = array(
            'obj_id'   => $role->id,
            'activity_type' => 'System',
            'action'        => 'Deleted',
            'description' => 'Deleted Role ' . $role->name
        );
        $role->users()->detach($id);
        $role->delete();

        // add log
        Activity::log($data);
        return $this->push('ok');
    }

    /**
     * Role Permission 
     *
     * @param  int   $id 
     * @return void
     */
    public function getRolePermission($id = '')
    {
        // 权限检查
        check_perms('view_role,edit_role');

        if ( ! $role = Role::find($id)) 
        {
            return Redirect::to('admin/setting/roles')->with('error', 'The Role is not exists!');
        }

        $perms = array();

        foreach ($role->perms as $perm) 
        {
            $perms[] = $perm->name;
        }

        $this->layout->content = View::make('admin.setting.role.permission')->with(array('role' => $role, 'perms' => $perms));
    }

    /**
     * Post Role Permission 
     *
     * @param  int   $id 
     * @return void
     */
    public function postRolePermission($id = '')
    {
        // 权限检查
        check_perms('view_role,edit_role');

        if ( ! $role = Role::find($id)) 
        {
            return Redirect::to('admin/setting/roles')->with('error', 'The Role is not exists!');
        }

        $actions = Input::get('action');
        $perms = array();
        if ($actions) 
        {
            foreach ($actions as $action) 
            {
                if ( ! $perm = Permission::where('name', '=', $action)->first()) 
                {
                    $perm = new Permission;
                    $perm->name = $action;
                    $perm->display_name = ucwords(str_replace('_', ' ', $action));
                    $perm->save();
                }
                $perms[] = $perm->id;
            }
        }
        $role->savePermissions($perms);
        return Redirect::back()->with('edit', TRUE);
    }
    
    /**
     * 
     *
     * @return void
     */
    public function getCreditLevel()
    {
    	// 权限检查
    	check_perm('view_credit_level');

    	$_objs = CreditLevel::get();
    
    	$this->data['objs'] = $_objs;
    	
    	$this->layout->content = View::make('admin.setting.credit.credit_level', $this->data);
    }

    public function getCreditLevelAdd()
    {
    	check_perm('write_credit_level');
    	
    	$this->layout->content = View::make('admin.setting.credit.credit_level_add', $this->data);
    }
    
    public function postCreditLevelAdd()
    {
    	check_perm('write_credit_level');
    	
    	$_messages = array(
    			'user_name.required'      	=> '请输入用户名',
    			'password.required'      	=> '请输入密码',
    			'limit_login' 				=> '您的登录过于频繁，请 1 小时后再尝试登录',
    	);
    	 
    	$_validation = Validator::make(Input::all(), array(
    			'level_name'	=> 'required',
    			'level'      	=> 'required|integer',
    			'credit_min'	=> 'required|integer',
    			'credit_max'	=> 'required|integer',
    			'margin_rate'	=> 'required|numeric',
    			'img_file'      => 'required',

    	), $_messages);
    	 
    	if ($_validation->fails())
    	{
    		return Redirect::back()->withInput()->withErrors($_validation);
    	}
    	
    	$_name 			= trim(Input::get('level_name'));
    	$_level 		= trim(Input::get('level'));
    	$_credit_min	= trim(Input::get('credit_min'));
    	$_credit_max 	= trim(Input::get('credit_max'));
    	$_margin_rate	= trim(Input::get('margin_rate'));
    	$_img_file	 	= trim(Input::get('img_file'));
    	
    	$_obj				= new CreditLevel;
    	$_obj->name 		= $_name;
    	$_obj->level 		= $_level;
    	$_obj->credit_min 	= $_credit_min;
    	$_obj->credit_max 	= $_credit_max;
    	$_obj->margin_rate 	= round($_margin_rate, 2);
    	$_obj->image 		= $_img_file;
    	$_obj->desc 		= '';
    	
    	$_obj->save();
    	
    	return Redirect::to('/admin/setting/credit-level');
    }
    
    public function getCreditLevelRemove($obj_id)
    {
    	// 权限检查
    	check_perm('write_credit_level');
    	
    	CreditLevel::where('id', $obj_id)->delete();
    	
    	return Redirect::back();
    }
}
