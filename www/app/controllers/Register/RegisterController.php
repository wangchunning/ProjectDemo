<?php namespace Controllers\Register;

use View;
use Validator;
use Input;
use Redirect;
use Request;
use Carbon\Carbon;
use Hash;
use Auth;
use User;
use App;
use Ip2loc;
use Controllers\BaseController;
use Event;
use DB;

use Ret;
use UserLoginLog;
use UserService;

/**
 *  注册控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class RegisterController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.simple'; 

    /**
     * Inject user model to the current container
     *
     * @param WeXchange\Model\User     $user
     * @param WeXchange\Model\Business $business
     */
    public function __construct()
    {

    }

    /**
     * 显示注册介绍页面
     * 
     * @return \Response
     */
    public function getIndex()
    {
       $this->layout->content = View::make('register.personal'); 
    }  

    /**
     * 显示个人账号注册页面
     * 
     * @return \Response
     */
    public function getPersonal()
    {
        $this->layout->content = View::make('register.personal');
    }

    /**
    * 提交个人账号注册
    *
    * @return Redirect
    */
    public function postPersonal()
    {          
        $_messages = array(
            'user_name.unique'        => '用户名已被占用',
            'password:min'            => '您的密码长度太短，最少 8 个字符',
        );
        
        Input::merge(array_map('trim', Input::all()));
        
        $_validator = Validator::make(Input::all(), array(
            'email'         => 'required|email',
            'password'      => 'required|min:8|confirmed',
            'user_name'     => 'required|min:4|max:16|unique:users',
            //'mobile'        => 'required',
        ), $_messages);

        if ($_validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($_validator);
        }  

        // 创建新用户
        if (UserService::create_user_and_login(Input::all(), 
        		UserLoginLog::LOGIN_FROM_WEB) === Ret::RET_FAILED)
        {
        	return Redirect::back()->withInput()->with('error', '暂时无法成功注册用户，请稍后重试');
        }

        return Redirect::to('/');      
    }


   /**
     * 邀请用户
     *
     * @param  int   $id 
     * @return Response
     */
    public function getBusinessMemberCheckin($id, $token)
    {

        $invite = BusinessInvite::find($id);

        if (empty($invite) || $invite->invite_token != $token || 
                strtotime($invite->expires) < time()) 
        {
            App::abort(404, 'Page not found');
        }

        /**
         * 存储映射关系
         * 删除邀请信息
         */
        $user_mapping = UserMapping::where('parent_id', $invite->invitor_id)
                            ->where('user_id', $invite->invitee_id)
                            ->first();

        if (!empty($user_mapping))
        {
            return Redirect::to('Page not found');
        }

        try
        {
            /**
             * begin MySQL transaction
             *
             * All queries must be in one transaction
             */
           DB::beginTransaction();

            /**
             * update user mapping
             */
            $user_mapping = new UserMapping;
            $user_mapping->parent_id = $invite->invitor_id;
            $user_mapping->user_id  = $invite->invitee_id;
            $user_mapping->save();

            /**
             * delete business_invite
             */
            $invite->delete();

            /**
             * end MySQL transaction
             */
            DB::commit();            
        }
        catch (\PDOException $e)
        {
            /* rollback */
            DB::rollBack();
            App::abort(404, 'Page not found');
        }

        return Redirect::to('login');
    }

    public function postCheckAbn()
    {
        $obj = '';
        $number = str_replace('-', '', Input::get('abn'));
        $number = str_replace(' ', '', $number);

        $obj = ABNLookup::searchByAbn($number);
        if ($obj->status == 'Active')
        {
            return $this->push('ok', array('name' => $obj->entityName));
        }

        return $this->push('error', array('msg' => 'ABN/ACN 无效'));
    }

    /**
     * 通过邀请邮件进入管理员注册页
     *
     * @param  int      id of the user_invite
     * @param  string   Validation token
     * @return Redirect
     */
    public function getManagerSignup($id, $token)
    {
         $invite = UserInvite::find($id);

         if (empty($invite) OR $invite->invite_token != $token OR strtotime($invite->expires) < time()) App::abort(404, 'Page not found');

         $this->layout->content = View::make('register.manager')->with('invite', $invite);
    }

    /**
     * 保存管理员注册信息
     *
     * @param  int      id of the user_invite
     * @param  string   Validation token
     * @return Redirect
     */
    public function postManagerSignup($id, $token)
    {
         $invite = UserInvite::find($id);

         if (empty($invite) OR $invite->invite_token != $token OR strtotime($invite->expires) < time()) App::abort(404, 'Page not found');

         $messages = array(
            'email.unique'           => 'Email 已被注册，请联系 info@anying.com',
            'password:min'           => '抱歉，您的密码长度太短了'
        );

        $validator = Validator::make(Input::all(), array(
            'email'                  => 'required|email|unique:users',
            'password'               => 'required|min:8|confirmed',
            'first_name'             => 'required',
            'last_name'              => 'required'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::to(sprintf('admin/users/signup/%s/%s',$id, $token))->withInput()->withErrors($validator);
        }

        $email = Input::get('email');
        if ($email != $invite->email) 
        {
           return Redirect::to(sprintf('admin/users/signup/%s/%s',$id, $token))->withInput()->with(array('error' => TRUE));
        }

        // 创建新用户
        $userId = $this->createNewUser('manager');

        // 自动登录
        Auth::loginUsingId($this->user->uid);
        // Fire an event to update the login timestamps
        $this->user->save_access_log();

        $roles = explode('|', $invite->roles);
        $this->user->roles()->attach($roles);

        $invite->delete();
        return Redirect::to('admin');
    }

    /**
     * 创建用户信息
     * 
     * @param  string 用户类型：User::TYPE_VALUE_XXX
     * @return int 用户 id
     */
    protected function createNewUser($type)
    {
        $reg_loc = Ip2loc::geoip(Request::getClientIp());

        // 创建用户
        $this->user->email      = Input::get('email');
        $this->user->password   = Hash::make(Input::get('password'));
        $this->user->type       = $type;
        $this->user->user_name  = Input::get('name');
        $this->user->reg_ip     = Request::getClientIp();
        if ($reg_loc && $reg_loc->city != null) 
        {
            //$this->user->reg_country = $reg_loc->country_name;
            $this->user->reg_city = $reg_loc->city;
            //$reg_loc->timezone ? ($this->user->timezone = $reg_loc->timezone) : '';
        }  
        $this->user->reg_at = Carbon::now()->toDateTimeString();

        $this->user->mobile         = Input::get('mobile');
        $this->user->id_number      = Input::get('id_number');

        $this->user->addr_street    = Input::get('street');
        $this->user->addr_city      = Input::get('city');
        $this->user->addr_state     = Input::get('state');
        $this->user->addr_postcode  = Input::get('postcode');
        $this->user->save();

        return $this->user->uid;
    }
}
