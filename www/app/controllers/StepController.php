<?php namespace Controllers;

use Route;
use Session;
use Redirect;

/**
 *  步骤控制器
 *
 * 需要按指定的顺序访问步骤，否则跳回上一步骤
 *  
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
Class StepController extends AuthController {

    /**
     * 保存需按顺序的步骤
     *
     * @var array
     */
    protected $steps = array();

    /**
     * 用于存放步骤的 Session 前缀
     *
     * @var  string
     */
    protected $prefix = '';

    public function __construct($className = '', $steps = array())
    {
        parent::__construct();

        $this->prefix = $className;

        $this->steps = $steps;

        $this->initialize();
    }

    /**
     * 初始化 
     *
     * @return void
     */
    public function initialize()
    {
        if ( ! $this->steps)
        {
            return;
        }

        foreach ($this->steps as $step)
        {
            $step = $this->prefix . $step;

            if (Session::has($step))
            {
                continue;
            }

            Session::put($step, 0);
        }
    } 

    /**
     * 步骤检查
     *
     * @return mixed
     */
    public function stepCheck()
    {
        // 找出当前操作是属于哪个步骤
        $key = array_search(current_method(), $this->steps);

        // 没有该步骤?
        if ($key === FALSE)
        {
            return;
        }

        // 属于第一个步骤?清除之前操作
        if ($key === 0)
        {
            $this->clearSteps();
            return;
        }

        // 该步骤的上一个步骤未完成？跳回上一步骤
        if (Session::get($this->prefix . $this->steps[$key - 1]) === 0)
        {
            $action = explode('@', Route::currentRouteAction());
            $action = reset($action);

            return Redirect::action($action . '@' . $this->steps[$key - 1]);
        }   
    }

    /**
     * 设置完成的步骤
     *
     * @param  string
     * @return void
     */
    public function stepDone($step = '')
    {
        // 找出当前操作属于哪个步骤
        $key = array_search($step, $this->steps);

        // 完成了最后一个步骤？重置步骤
        if ($key == count($this->steps) - 1)
        {
            $this->clearSteps();
        }

        $step = $this->prefix . $step;

        // 设置该步骤已完成
        if (Session::has($step))
        {
            Session::put($step, 1);
        }
    }

    /**
     * 重置步骤
     *
     * @return void
     */
    public function clearSteps()
    {
        foreach ($this->steps as $step)
        {
            $step = $this->prefix . $step;

            Session::forget($step);
        }        
    }
}