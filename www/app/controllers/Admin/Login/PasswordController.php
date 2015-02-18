<?php namespace Controllers\Admin;

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
//use WeXchange\Model\Pwd_reminder;
use EmailProvider;
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
    protected $layout = 'layouts.admin_login';   

    /**
     * User model
     *
     * @var Illuminate\Auth\UserInterface
     */
    protected $user;

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
    public function __construct(User $user, Pwd_reminder $p_reminder)
    {
        $this->user = $user;

        $this->p_reminder = $p_reminder;

        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a simple form
     *
     * @return Response
     */
    public function getIndex()
    {  
        $this->layout->content = View::make('admin.password.index');
    }

    /**
     * Validate the email and send a email
     *
     * @return Redirect
     */
    public function postIndex()
    {
        $validation = Validator::make(Input::all(), array(
            'email'     => array('required', 'email')
        ));

        // Basic validation has failed.
        if ($validation->fails())
        {
            return Redirect::to('admin/password')->withErrors($validation);
        }

        $email = trim(Input::get('email'));
    
        if ($user = $this->user->where('email', '=', $email)->where('type', '=', 'manager')->first())
        {
            Password::remind(array('email' => $email), function($message, $user)
            {
                $message->subject('WeXchange Password reset');
            });

            // Authentication passed.
            return Redirect::to('admin/password/email-sent')->with('email', $email);
        }

        // Authentication failed.
        return Redirect::to('admin/password')->with(array('error' => TRUE));
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
            return Redirect::to('admin/password');
        }
        $email = EmailProvider::resolve($email);
        $this->layout->content = View::make('admin.password.email')->with('email', $email);
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
            return Redirect::to('admin/login');
        }

        // The reminder repository is responsible for storing the user e-mail addresses
        // and password reset tokens. It will be used to verify the tokens are valid
        // for the given e-mail addresses. We will resolve an implementation here.
        $reminders = App::make('auth.reminder.repository');
        if( ! $reminders->exists($user, $token))
        {
            return Redirect::to('admin/password');
        }

        $this->layout->content = View::make('admin.password.reset', array(
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
            return Redirect::action('Controllers\Admin\PasswordController@getReset', array(
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
            if ( ! is_login('admin')) 
            {
                Auth::admin()->attempt(array('email' => Input::get('email'), 'password' => Input::get('password'), 'type' => 'manager'));
                // Fire an event to update the login timestamps
                Auth::admin()->user()->save_access_log();
            }

            return Redirect::to('admin')->with('reseted', TRUE);
        });
    }
}
