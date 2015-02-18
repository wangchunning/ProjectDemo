<?php namespace Tt\TXSession;

use Session;
use Carbon;

use App;

use Auth;

/**
 *  
 *
 */
class TXSession 
{


    private $_session_prefix    = '__TT_TX__1__';

    public function init($session_name)
    {

        $_session_name = $this->_makeSessionName($session_name);

        if ($this->check($session_name))
        {
            Session::forget($_session_name);
        }
    }

    public function get($session_name)
    {
        $_session_name = $this->_makeSessionName($session_name);

        return unserialize(Session::get($_session_name));
    }

    public function get_and_forget($session_name)
    {
        $_view = $this->get($session_name);
        $this->forget($session_name);
        
        return $_view;
    }
       
    public function put($session_name, $view)
    {
        $_session_name = $this->_makeSessionName($session_name);

        return Session::put($_session_name, serialize($view));
    }

    public function forget($session_name)
    {
        $_session_name = $this->_makeSessionName($session_name);
        return Session::forget($_session_name);
    }

    /**
     * 
     *
     * @return bool
     */
    public function check($session_name)
    {
        $_session_name = $this->_makeSessionName($session_name);

        return Session::has($_session_name);
    }

    private function _makeSessionName($session_name)
    {
        return $this->_session_prefix . $session_name;
    }
}