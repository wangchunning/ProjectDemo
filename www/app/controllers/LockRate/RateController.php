<?php namespace Controllers\LockRate;

use View;
use Input;
use Rate;
use Auth;
use Carbon\Carbon;
use Controllers\AuthController;
use Breadcrumb;

/**
 *  Rate Controller
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class RateController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     *  List for rate
     *
     * @return void
     */
    public function getIndex()
    {
        $this->data['user'] = real_user();

        $this->data['rates'] = Rate::all();

        // set breadcrumb
        Breadcrumb::map(array('我的账户' => url('home')))->append('汇率');

        $this->layout->content = View::make('lockrate.rate.index', $this->data);        
    }  

}