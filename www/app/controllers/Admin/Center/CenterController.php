<?php namespace Controllers\Admin;

use View;
use Administrator;
use Role;
use Auth;
use Controllers\AdminController;
use Log;
/**
 *  后台用户首页控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class CenterController extends AdminController {

    /**
     * User model
     *
     * @var WeXchange\Model\User
     */
    protected $user;

    public function __construct(Administrator $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * 显示用户主页
     *
     * @return void
     */
    public function getIndex()
    {

        $this->data['manager'] = login_user(Administrator::LABEL);

        $this->data['business_finance'] = array();

        $this->data['non_perform_asset'] = array();
        $this->data['gov_asset'] = array();
        $this->data['credit_asset'] = array();
        
        $this->layout->content = View::make('admin.center.index', $this->data);
    }
}
