<?php namespace Controllers\Admin;

use View;
use Validator;
use Input;
use Activity;
use Auth;
use User;
use Role;
use Request;
use Controllers\AdminController;

/**
 *  后台用户操作记录控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class LogController extends AdminController {

    /**
     * Activity model
     *
     * @var WeXchange\Model\Activity
     */
    protected $act;

    public function __construct(Activity $act)
    {
        parent::__construct();

        $this->act = $act;
    }

    /**
     * 显示后台用户操作记录列表
     *
     * @return void
     */
    public function getIndex()
    {
        if ( ! $user = User::find(Input::get('uid')) OR $user->uid != Auth::admin()->user()->uid)
        {
            // 权限检查
            check_perms('view_log');
        }

        $managers = User::where('type', '=', 'manager')->get();

        $roles = Role::all()->toArray();

        $args = array();

        $logs = $this->act
            ->where('user_type', '=', 'manager')
            ->where(function($q) use (&$args)
            {
                // 可查询的字段
                $fields = array('uid', 'activity_type', 'role', 'start', 'expriy', 'dw');

                $query = Request::query();
                foreach ($query as $k => $v) 
                {
                    // 查询的值为空
                    if ( ! $v) continue;

                    if (in_array($k, $fields)) 
                    {
                        switch ($k) 
                        {
                            case 'start':
                                $q->where('user_logs.created_at', '>=', time_to_search(urldecode($v)));
                                break;

                            case 'expriy':
                                $q->where('user_logs.created_at', '<=', time_to_search(urldecode($v)));
                                break;
                            
                            case 'role':
                                $q->whereIn('uid', function($query) use($v)
                                {
                                    $query->select('user_id')
                                          ->from('role_user')
                                          ->where('role_id', $v);
                                });
                                break;

                            case 'dw':
                                $q->where('created_at', '>', word_to_date($v));
                                break;

                            default:
                                $q->where($k, '=', $v);
                                break;
                        }                     
                        // 记录当前查询字段
                        $args[$k] = $v;
                    }
                }
            })
            ->orderBy('user_logs.id', 'desc');

        $total_count = $logs->count();

        $logs = $logs->take(parent::DEFAULT_SHOW_MORE_ENTRIES)->get();

        $this->layout->content = View::make('admin.center.log.index')
            ->with(array(
                'managers' => $managers,
                'roles'    => $roles,
                'activities' => array(
                    'Transactions'  => 'Transactions', 
                    'Customers'     => 'Customers', 
                    'Reports'       => 'Reports', 
                    'Rate'          => 'Rate', 
                    'Banks'         => 'Banks',
                    'Users'         => 'Users', 
                    'System'        => 'System',
                    'Billing'       => 'Billing'
                ),
                'logs' => $logs, 
                'args' => $args, 
                'total_count'   => $total_count,
                'user' => $user ? $user : ''
                ));
    }

    /**
     * for ajax, send json to view
     *
     * @return void
     */
    public function getJson($page_idx = 1)
    {
        $_page_idx = $page_idx;

        $user = (Input::get('uid') AND User::find(Input::get('uid'))) ? User::find(Input::get('uid')) : '';

        $args = array();

        $logs = $this->act
            ->where('user_type', '=', 'manager')
            ->where(function($q) use (&$args)
            {
                // 可查询的字段
                $fields = array('uid', 'activity_type', 'role', 'start', 'expriy', 'dw');

                $query = Request::query();
                foreach ($query as $k => $v) 
                {
                    // 查询的值为空
                    if ( ! $v) continue;

                    if (in_array($k, $fields)) 
                    {
                        switch ($k) 
                        {
                            case 'start':
                                $q->where('user_logs.created_at', '>=', time_to_search(urldecode($v)));
                                break;

                            case 'expriy':
                                $q->where('user_logs.created_at', '<=', time_to_search(urldecode($v)));
                                break;
                            
                            case 'role':
                                $q->whereIn('uid', function($query) use($v)
                                {
                                    $query->select('user_id')
                                          ->from('role_user')
                                          ->where('role_id', $v);
                                });
                                break;

                            case 'dw':
                                $q->where('created_at', '>', word_to_date($v));
                                break;

                            default:
                                $q->where($k, '=', $v);
                                break;
                        }                     
                        // 记录当前查询字段
                        $args[$k] = $v;
                    }
                }
            })
            ->orderBy('user_logs.id', 'desc');

        $total_count = $logs->count();

        $logs = $logs->take(parent::DEFAULT_SHOW_MORE_ENTRIES)->offset(parent::DEFAULT_SHOW_MORE_ENTRIES * ($_page_idx - 1))->get();

        /* make json */
        $_aa_data = array();
        foreach ($logs as $log)
        {
            $_row = array();

            $_row[] = date_word($log->created_at);

            if ( ! $user OR $user->uid != Auth::admin()->user()->uid)
            {                
                $_row[] = link_to('admin/logs/?uid=' . $log->user->uid, pretty_str($log->user->full_name));      
            }
                   
            $_row[] = $log->action ;
            
            $_row[] = $log->activity_type;

            $_row[] = $log->description;

            $_aa_data[] = $_row;
        }
                        
        $output = array(
            "aaData"            => $_aa_data,
            "total_count"       => $total_count,
        );

        return json_encode($output);
    }
}
