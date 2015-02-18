<?php namespace Controllers\Admin;

use View;
use Input;
use Rate;
use BasicRate;
use Activity;
use Validator;
use Auth;
use Carbon\Carbon;
use Controllers\AdminController;
use TransactionFee;

/**
 *  汇率管理控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class RateController extends AdminController {

    /**
     * Rate model
     *
     * @var WeXchange\Model\Rate
     */
    protected $rate;

    /**
     * Basic rate model
     *
     * @var WeXchange\Model\BasicRate
     */
    protected $basicRate;
 
    public function __construct(Rate $rate, BasicRate $basicRate)
    {
        parent::__construct();

        $this->rate = $rate;
        $this->basicRate = $basicRate;
    }

    /**
     * 汇率列表
     *
     * @return void
     */
    public function getIndex()
    {
        // 权限检查
        check_perms('view_rate,view_basic_rate', 'OR');

        // 货币列表
        $this->data['currencies'] = $this->rate->getCurrencies();

        $currencies = array_diff($this->data['currencies'], array('AUD', 'USD', 'HKD', 'NZD'));

        $this->data['rates'] = $this->rate->orderByRaw("FIELD(`from`, 'AUD', 'USD', 'HKD', 'NZD', '" . implode($currencies, '\',\'') . "')")
            ->orderBy('order', 'asc')
            ->get();
        /**
         * v0.9, 只有一种费用
         */
        $this->data['tx_fee'] = TransactionFee::first();

        $this->layout->content = View::make('admin.center.rate.index', $this->data);        
    }

    /**
     * 提交货币汇率
     *
     * @return Response
     */
    public function postAdd()
    {
        // 权限检查
        check_perms('view_rate,add_rate');

        $localCurrency   = Input::get('from');
        $foreignCurrency = Input::get('to');

        // 两个货币相同？不能添加
        if ($localCurrency == $foreignCurrency)
        {
            return $this->push('error');
        }

        // 已有记录或是反向记录，不能添加
        $rate = $this->rate
                     ->where(function($query) use ($localCurrency, $foreignCurrency)
                     {
                        $query->where('from', '=', $localCurrency)
                              ->where('to', '=', $foreignCurrency);
                     })
                     ->orWhere(function($query) use ($localCurrency, $foreignCurrency)
                     {
                        $query->where('from', '=', $foreignCurrency)
                              ->where('to', '=', $localCurrency);                
                     })
                     ->get();

        if ( ! $rate->isEmpty())
        {
            return $this->push('error');            
        }

        $this->rate->where('from', '=', $localCurrency)->where('to', '=', $foreignCurrency)->delete();
        $this->rate->from = $localCurrency;
        $this->rate->to = $foreignCurrency;
        $this->rate->save();

        // add log
        Activity::log(array(
            'activity_type' => 'Rate',
            'action'        => 'Add',
            'description' => 'Add New Rate ' . sprintf('%s/%s', $this->rate->from, $this->rate->to)
        ));

        return $this->push('ok');
    }

    /**
     * 移除货币汇率
     *
     * @return Response
     */
    public function postRemove()
    {
        // 权限检查
        check_perms('view_rate,remove_currency');

        $currencyFrom = substr(Input::get('currency'), 0, 3);
        $currencyTo = substr(Input::get('currency'), 3);

        $rate = $this->rate->where('from', '=', $currencyFrom)->where('to', '=', $currencyTo)->delete();

        // add log
        Activity::log(array(
            'activity_type' => 'Rate',
            'action'        => 'Deleted',
            'description' => 'Deleted Rate ' . sprintf('%s/%s', $currencyFrom, $currencyTo)
        ));
        
        return $this->push('ok');        
    }

    /**
     * 提交 rate 修改
     *
     * @return Response
     */
    public function postEdit()
    { 
        // 权限检查
        check_perm('view_rate');
        check_perms('edit_rate_margin,edit_vip_margin', 'OR');

        $currency_rate  = Input::get('rate');
        $tx_fee_str     = Input::get('fee');
        
        $change = array();
        $edit_margin = check_perm('edit_rate_margin', FALSE);
        $edit_vip = check_perm('edit_vip_margin', FALSE);

        $tx_fees = explode('|', $tx_fee_str[0]);
        
        $updated_arr['amount']          = $tx_fees[0];      //basic_fee
        $updated_arr['silver_amount']   = $tx_fees[1];      //silver_fee
        $updated_arr['golden_amount']   = $tx_fees[2];      //golden_fee
        $updated_arr['platinum_amount'] = $tx_fees[3];      //platinum_fee

        $affect_rows = TransactionFee::where('type', 'Withdraw')
                            ->update($updated_arr);

        // add log
        if ($affect_rows > 0)
        {
            Activity::log(array(
                'activity_type' => 'Transaction Fee',
                'action'        => 'Updated',
                'description' => 'Updated Withdraw Transaction Fee'
            ));
        }

        foreach ($currency_rate as $k => $rate_data) 
        {
            /**
             * v0.9, add more vip types:
             *     silver, golden, platinum
             */
            $c_rate = explode('|', $rate_data);
            $currency_from      = substr($c_rate[0], 0, 3);
            $currency_to        = substr($c_rate[0], 3);
            $basic_bid_margin   = $c_rate[1];
            $basic_ask_margin   = $c_rate[2];
            $silver_bid_margin  = $c_rate[3];
            $silver_ask_margin  = $c_rate[4];
            $golden_bid_margin  = $c_rate[5];
            $golden_ask_margin  = $c_rate[6];
            $platinum_bid_margin = $c_rate[7];
            $platinum_ask_margin = $c_rate[8];

            $rate = $this->rate
                        ->where('from', '=', $currency_from)
                        ->where('to', '=', $currency_to);

            // 是否有权限修改margin
            $updated_arr = array();
            if ($edit_margin)
            {
                $updated_arr['bid_margin'] = $basic_bid_margin;
                $updated_arr['ask_margin'] = $basic_ask_margin;
            }
            // 是否有权限修改 VIP margin
            if ($edit_vip) 
            {
                $updated_arr['silver_bid_margin'] = $silver_bid_margin;
                $updated_arr['silver_ask_margin'] = $silver_ask_margin;
                $updated_arr['golden_bid_margin'] = $golden_bid_margin;
                $updated_arr['golden_ask_margin'] = $golden_ask_margin;
                $updated_arr['platinum_bid_margin'] = $platinum_bid_margin;
                $updated_arr['platinum_ask_margin'] = $platinum_ask_margin;
            } 

            if (empty($updated_arr))
            {
                continue;
            }        

            // real update
            $affect_rows = $rate->update($updated_arr);   
            if ($affect_rows > 0)   // if updated
            {
                $change[$k] = sprintf('%s/%s', $currency_from, $currency_to);
            }
        }

        // add log
        if (!empty($change))
        {
            Activity::log(array(
                'activity_type' => 'Rate',
                'action'        => 'Updated',
                'description' => 'Updated Margin ' . implode(', ', $change)
            ));
        }

        return $this->push('ok');
    }

    /**
     * 获取 Basic rate
     *
     */
    public function getBasic()
    {
        // 权限检查
        check_perm('view_basic_rate');

        $offset = Input::get('perpage') == 1 ? 0 : Input::get('perpage');


        $rates = $this->basicRate->take(5)->offset($offset)->orderBy('id', 'desc')->get();

        return View::make('admin.center.rate.basic')->with('rates', $rates);
    }

    /**
     * 移除基础汇率
     *
     * @return Response
     */    
    public function postBasicRemove()
    {
        // 权限检查
        check_perms('view_basic_rate,delete_basic_rate');

        $basic = $this->basicRate->find(Input::get('id'));

        $data = array(
            'obj_id'        => $basic->id,
            'activity_type' => 'Rate',
            'action'        => 'Deleted',
            'description' => 'Deleted basic Rate ' . $basic->rate
        );

        $basic->delete();

        // add log
        Activity::log($data);

        return $this->push('ok');
    }

    /**
     * 提交 Basic rate
     *
     * @return Response
     */
    public function postBasic()
    {
        // 权限检查
        check_perms('view_basic_rate,edit_basic_rate');

        $validator = Validator::make(Input::all(), array(
            'usdcny_rate' => 'required|numeric',
        ));

        if ($validator->fails())
        {            
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }  

        $this->basicRate->day = date('Y-m-d', strtotime(Carbon::now()));
        $this->basicRate->currency = 'USDCNY';
        $this->basicRate->rate = Input::get('usdcny_rate');
        $this->basicRate->operator_name = Auth::admin()->user()->full_name;
        $this->basicRate->operator_id = Auth::admin()->user()->uid;
        $this->basicRate->save();

        // add log
        Activity::log(array(
            'obj_id'        => $this->basicRate->id,
            'activity_type' => 'Rate',
            'action'        => 'Add',
            'description' => 'Add Basic Rate ' . $this->basicRate->rate
        ));

        return $this->push('ok');
    }

    /**
     * Ajax,提交排序
     *  
     * @return Response
     */
    public function postOrder()
    {
        foreach (Input::get('items') as $order => $item)
        {
            Rate::where('from', '=', substr($item, 0, 3))
                ->where('to', '=', substr($item, 3))   
                ->update(array('order' => $order));
        }

        return $this->push('ok');
    }
}
