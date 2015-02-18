<?php namespace Controllers\Login;

use Controllers\BaseController;
use View;
use Auth;
use Redirect;
use Validator;
use Input;
use Password;
use Session;
use Hash;
use App;
use User;
//use WeXchange\Model\Verification;
use Captcha;
//use WeXchange\Model\Pwd_reminder;
use Sms;
use EmailProvider;
use Queue;
use Event;

/**
 *  密码管理控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class PasswordController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.simple';   

    /**
     * User model
     *
     * @var Illuminate\Auth\UserInterface
     */
    protected $user;

    /**
     * Verification model
     *
     * @var WeXchange\Model\Verification
     */
    protected $ver;

    /**
     * Pwd_reminder model
     *
     * @var WeXchange\Model\Pwd_reminder
     */
    protected $p_reminder;

    /**
     * Inject user model to the current container
     *
     * @param  Illuminate\Auth\UserInterface  $user
     * @return void
     */
    public function __construct(User $user, Verification $ver, Pwd_reminder $p_reminder)
    {
        $this->user = $user;

        $this->ver = $ver;

        $this->p_reminder = $p_reminder;

        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a simple form
     *
     * @return Response
     */
    public function getIndex($reset_by = NULL)
    {  
        if ( ! $reset_by AND ! $reset_by = Session::get('reset_by'))
        {
            $reset_by = 'email';
        }
        $this->layout->content = View::make('password.index', array('reset_by' => $reset_by));
    }

    /**
     * Validate the email and send a email
     *
     * @return Redirect
     */
    public function postIndex()
    {
        $reset_by = Input::get('reset_by');

        $valid = $reset_by == 'email' ? 'required|email' : 'required';

        $validation = Validator::make(Input::all(), array(
            $reset_by     => $valid
        ));

        // Basic validation has failed.
        if ($validation->fails())
        {
            return Redirect::to('password')->with('reset_by', $reset_by)->withErrors($validation);
        }

        if ($reset_by == 'email') 
        {
            $verify_code = trim(Input::get('captcha'));
            
            if ( ! Captcha::check($verify_code))
            {
                return Redirect::to('password')->with(array('error' => TRUE, 'captcha_e' => TRUE, 'reset_by' => $reset_by));
            }

            $email = trim(Input::get('email'));
        
            if ($user = $this->user->where('email', '=', $email)->where('type', '=', 'user')->first())
            {
                Password::remind(array('email' => $email), function($message, $user)
                {
                    $message->subject('重置账号密码 - Anying');
                });

                // Authentication passed.
                return Redirect::to('password/email-sent')->with('email', $email);
            }
        }
        else
        {
            $mobile = trim(Input::get('phone_number'));

            if ($ver = $this->ver->where('security_mobile', '=', $mobile)->first())
            {
                if ($ver->security_verified) 
                {
                    $pin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

                    $user = $this->user->where('uid', '=', $ver->uid)->first();
                    $token = $this->createNewToken($user, $pin);

                    $this->p_reminder->mobile = $mobile;
                    $this->p_reminder->secure_pin = $pin;
                    $this->p_reminder->email = $user->email;
                    $this->p_reminder->token = $token;
                    $this->p_reminder->save();

                    $phone_code = '61';
                    if (isset($ver->phone_code) && !empty($ver->phone_code))
                    {
                        $phone_code = $ver->phone_code;
                    }

                    // 发送 SMS
                    $sms = array(
                        'to'   => '+' . $phone_code . $mobile,
                        'text' => '欢迎使用 Anying 换汇系统! 要重置密码，请输入验证码：' . $pin
                    );
                    Queue::push(function($job) use ($sms)
                    {
                        Sms::send($sms);
                        
                        $job->delete();
                    }); 

                    // Authentication passed.
                    return Redirect::to('password/sms-sent')->with('mobile', $mobile);
                }      
            }
        }

        // Authentication failed.
        return Redirect::to('password')->with(array('error' => TRUE, 'reset_by' => $reset_by));
    }

    /**
     * Notify the user we've sent the email
     *
     * @return Response
     */
    public function getEmailSent()
    {
        Session::reflash();

        if ( ! $email = Session::get('email'))
        {
            return Redirect::to('password');
        }

        $email = EmailProvider::resolve($email);
        $this->layout->content = View::make('password.email')->with('email', $email);
    }

    /**
     * Reset password by pin
     *
     * @return Response
     */
    public function getSmsSent()
    {
        Session::reflash();

        if ( ! $mobile = Session::get('mobile'))
        {
            return Redirect::to('password');
        }
        $this->layout->content = View::make('password.mobile')->with('mobile', $mobile);
    }

    /**
     * Validate the pin and continue to reset password
     *
     * @return Response
     */
    public function postSmsSent()
    {
        Session::reflash();

        if ( ! $mobile = Session::get('mobile'))
        {
            return Redirect::to('password');
        }

        $validation = Validator::make(Input::all(), array(
            'pin'     => 'required'
        ));

        // Basic validation has failed.
        if ($validation->fails())
        {
            return Redirect::to('password/sms-sent')->with('mobile', $mobile)->withErrors($validation);
        }

        if ($reminder = $this->p_reminder->where('secure_pin', '=', Input::get('pin'))->first())
        {
            // 手机号不正确
            if ($reminder->mobile != $mobile) 
            {
                return Redirect::to('password/sms-sent')->with(array('mobile' => $mobile, 'error' => TRUE, 'msg' => '验证码不正确!'));
            }

            // pin已过期
            $expired = strtotime($reminder->created_at) + 60;
            if ($expired < time()) 
            {
                return Redirect::to('password/sms-sent')->with(array('mobile' => $mobile, 'error' => TRUE, 'msg' => '验证码已过期!'));
            }

            $user = $this->user->where('email', '=', $reminder->email)->first();
            return Redirect::to('password/reset/' . $user->uid . '/' . $reminder->token);
        }
        return Redirect::to('password/sms-sent')->with(array('mobile' => $mobile, 'error' => TRUE, 'msg' => '验证码不正确!'));
    }

    /**
     * Display a reset form
     *
     * @param  int      $uid User uid
     * @param  string   $token Reset token
     * @return mixed    Response or Redirect
     */
    public function getReset($uid, $token)
    {
        // Make sure the user exists and is valid
        $user = User::find($uid);

        if ( is_null($user) OR ! $user instanceof User)
        {
            return Redirect::to('login');
        }

        // The reminder repository is responsible for storing the user e-mail addresses
        // and password reset tokens. It will be used to verify the tokens are valid
        // for the given e-mail addresses. We will resolve an implementation here.
        $reminders = App::make('auth.reminder.repository');
        if( ! $reminders->exists($user, $token))
        {
            return Redirect::to('/');
        }

        $this->layout->content = View::make('password.reset', array(
            'token'     => $token,
            'email'     => $user->email,
            'uid'       => $user->uid
        ));
    }

    /**
     * Reset password for the current user
     *
     * @return Redirect
     */
    public function postReset()
    {
        $validation = Validator::make(Input::all(), array(
            'password'                  => array('required', 'min:8', 'Confirmed'),
            'password_confirmation'     => array('required', 'min:8')
        ));

        if ($validation->fails())
        {
            return Redirect::action('Controllers\Login\PasswordController@getReset', array(
                Input::get('uid'), 
                Input::get('token')
            ))->withErrors($validation);
        }

        $credentials = array('email' => Input::get('email'));
        
        return Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();

            // auto login
            if ( ! is_login()) 
            {
                Auth::member()->attempt(array('email' => Input::get('email'), 'password' => Input::get('password'), 'type' => 'user'));
                // Fire an event to update the login timestamps
                Auth::member()->user()->save_access_log();
            }

            return Redirect::to('home')->with('reseted', TRUE);
        });
    }

    /**
     * Create a new token for the user.
     *
     * @param  object $user
     * @param  string $pin
     * @return string
     */
    public function createNewToken($user, $pin)
    {
        $email = $user->getReminderEmail();

        $value = str_shuffle(sha1($email.spl_object_hash($this).microtime(true)));

        return hash_hmac('sha1', $value, $pin);
    }
}
