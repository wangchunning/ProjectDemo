<?php namespace WeXchange\Model;

use Transactions;
use BillingDetail;
use DB;
use Rate;

class Billing extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    public $incrementing = FALSE;

    /**
     * Enable soft deletes for the model
     *
     * @var bool
     */
    protected $softDelete = TRUE;    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'billings';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The property specifies which attributes should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = array('id', 'uid', 'status');

    /**
     * Get the user that the bill belongs to.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo('User', 'uid');
    }

    /**
     *  指定货币的 Unhandled 结算情况
     *
     * @param  string  $currency  货币
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return array
     */
    public function currencyDetailAccumulatedUnhandled($currency, $dw, $start, $expriy)
    {
        $output = array();

        $grossBillings = $this->currencyDetailAccumulatedGross($currency, $dw, $start, $expriy);

        $handledBillings = $this->currencyDetailAccumulatedHandled($currency, $dw, $start, $expriy);

        foreach ($grossBillings as $currency => $billing)
        {
            $currencyFrom = substr($currency, 0, 3);
            $currencyTo = substr($currency, 3);

            /**
             * 已结算的 In 数据
             *
             * 是否有对应的货币组已结算？没有则调换货币位置
             *
             * eg. 当前货币组是 AUDEUR,当结算中没有 AUDEUR ？看看有没 EURAUD 的结算
             */
            if (isset($handledBillings[$currency]))
            {
                $handledIn = $handledBillings[$currency]['in'];
            }
            elseif (isset($handledBillings[$currencyTo . $currencyFrom]))
            {
                $handledIn = $handledBillings[$currencyTo . $currencyFrom]['in'];        
            }
            else
            {
                $handledIn = 0;
            }

            /**
             * 已结算的 Out 数据
             *
             * 是否有对应的货币组已结算？没有则调换货币位置
             *
             * eg. 当前货币组是 AUDEUR,当结算中没有 AUDEUR ？看看有没 EURAUD 的结算
             */
            if (isset($handledBillings[$currency]))
            {
                $handledOut = $handledBillings[$currency]['out'];
            }
            elseif (isset($handledBillings[$currencyTo . $currencyFrom]))
            {
                $handledOut = $handledBillings[$currencyTo . $currencyFrom]['out'];        
            }
            else
            {
                $handledOut = 0;
            }            

            // 已有对应货币组刚将数据累积
            if (isset($output[$currency]))
            {
                $output[$currency]['in'] += $handledIn;
                $output[$currency]['out'] += $handledOut;
                continue;
            }
            
            if ($billing['in'] == $handledIn &&
                    $billing['out'] == $handledOut)
            {
                $output[$currency] = array(
                    'in'  => 0,
                    'out' => 0
                );
            }
            else
            {
                $output[$currency] = array(
                    'in'  => $billing['in'] + $handledIn,
                    'out' => $billing['out'] + $handledOut
                );
            }
        }

        return $output;
    }

    /**
     *  指定货币的 Handled 结算情况
     *
     * @param  string  $currency  货币
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return array
     */
    public function currencyDetailAccumulatedHandled($currency, $dw, $start, $expriy)
    {
        $output = array();
        /**
         * select currency_from, currency_to
         * from billing_detail
         * where currency_from = XXX. or currency_to = XXX
         * group by currency_from, currency_to
         * 
         */
        $billings = BillingDetail::select(
            array(
                'currency_from',
                'currency_to', 
            ))
            ->timearea($dw, $start, $expriy)
            ->where(function($query) use ($currency)
            {
                $query->where('currency_from', $currency)
                      ->orWhere('currency_to', $currency);
            })  
            ->groupBy('currency_from')
            ->groupBy('currency_to')            
            ->get();        

        /**
         * In:
         * 
         * select currency_from, currency_to, sum(bank_sell_amount) as `in`
         * from billing_detail
         * where currency_to = XXX
         * group by currency_from, currency_to
         */
        $Ins = BillingDetail::select(
            array(
                'currency_from',
                'currency_to', 
                DB::raw('sum(bank_sell_amount) as `in`'),
            ))
            ->timearea($dw, $start, $expriy)
            ->where('currency_to', $currency)
            ->groupBy('currency_from')
            ->groupBy('currency_to')            
            ->get()
            ->toArray();

        /**
         * Out:
         * 
         * select currency_from, currency_to, sum(bank_buy_amount) as `out`
         * from billing_detail
         * where currency_from = XXX
         * group by currency_from, currency_to
         */
        $Outs = BillingDetail::select(
            array(
                'currency_from',
                'currency_to', 
                DB::raw('sum(bank_buy_amount) as `out`'),
            ))
            ->timearea($dw, $start, $expriy)
            ->where('currency_from', $currency)
            ->groupBy('currency_from')
            ->groupBy('currency_to')            
            ->get()
            ->toArray();           

        foreach($billings as $key => $billing)
        {
            $output[$billing->currency_from . $billing->currency_to] = array(
                'in'  => isset($Ins[$key]) ? $Ins[$key]['in'] : 0,
                'out' => isset($Outs[$key]) ?  - $Outs[$key]['out'] : 0
            );
        }

        return $output;
    }

    /**
     * 指定货币的 Gross 结算情况
     *
     * @param  string  $currency  货币
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return array
     */
    public function currencyDetailAccumulatedGross($currency, $dw, $start, $expriy)
    {
        $output = array();

        $accs = Transaction::select(
            array(
                    'currency_from',
                    'currency_to', 
                    DB::raw('sum(amount) as `in`'),
                    DB::raw('sum(amount * rate) as `out`')))
            ->timearea($dw, $start, $expriy)
            ->where('type', 'FX Deal')
            ->where('status', 'Completed')
            ->where(function($query) use ($currency)
            {
                $query->where('currency_from', $currency)
                      ->orWhere('currency_to', $currency);
            })
            ->groupBy('currency_from')
            ->groupBy('currency_to')
            ->get();

        foreach ($accs as $acc)
        {
            if ($acc->currency_to == $currency)
            {
                $output[$acc->currency_from . $currency]['out'] =  - $acc->out;

                if ( ! isset($output[$acc->currency_from . $currency]['in']))
                {
                    $output[$acc->currency_from . $currency]['in'] = 0;
                }
            }
            else
            {
                $output[$acc->currency_to . $currency]['in'] = $acc->in;

                if ( ! isset($output[$acc->currency_to . $currency]['out']))
                {
                    $output[$acc->currency_to . $currency]['out'] = 0;
                }
            }
        } 

        
        //如果用户没有换汇，即tx为空，则 gross = handled
        return $output ? $output : $this->currencyDetailAccumulatedHandled($currency, $dw, $start, $expriy);      
    }


    public function gross_currency_accumulate($date_from, $date_to)
    {
        /* 
         * currancies need to show 
         *
         * select distinct `from` from rates
         */
        $_currencies_to_show = allCurrencies();

        /**
         * Gross - 某货币的累积 in
         * select currency_from, sum(amount) acc_in,
         * from transactions
         * where type = 'FX Deal' AND status = 'Completed' AND
         *         updated_at <= XXX
         * group by currency_from
         */
        $_gross_currency_in = Transaction::select(
                                    array(
                                            'currency_from', 
                                            DB::raw('sum(amount) as acc_in')))
                                ->where('updated_at', '>=', $date_from)
                                ->where('updated_at', '<=', $date_to)
                                ->where('type', 'FX Deal')
                                ->where('status', 'Completed')
                                ->groupBy('currency_from')
                                ->get();

        $_gross_arr = array();
        foreach ($_gross_currency_in as $_h_currency)
        {
            $__key = $_h_currency->currency_from;
            $_gross_arr[$__key]['in'] = $_h_currency->acc_in;
        }
        /** 
         * Gross - 某货币的累积 out
         * select currency_to, sum(amount * rate) acc_out,
         * from transactions
         * where type = 'FX Deal' AND status = 'Completed' AND
         *         updated_at <= XXX
         * group by currency_to
         */
        $_gross_currency_out = Transaction::select(
                                    array(
                                            'currency_to', 
                                            DB::raw('sum(amount * rate) as acc_out')))
                                ->where('updated_at', '>=', $date_from)
                                ->where('updated_at', '<=', $date_to)
                                ->where('type', 'FX Deal')
                                ->where('status', 'Completed')
                                ->groupBy('currency_to')
                                ->get();

        foreach ($_gross_currency_out as $_h_currency)
        {
            $__key = $_h_currency->currency_to;
            $_gross_arr[$__key]['out'] = $_h_currency->acc_out;
        }

        /**
         * handled in/out from billing_detail table
         */
        $_handled_arr = $this->handled_currency_accumulate($date_from, $date_to);
        /**
         * make result
         * array (
         *         'AUD' => array('in' => 100, 'out' => 200)
         *         'USD' => array('in' => 200, 'out' => 300)
         *     )
         */
        foreach ($_currencies_to_show as $_currency)
        {
            /**
             * 如果用户没有换汇，即tx为空，则 gross = handled
             * 如果用户有换汇，即tx非空，则 unhandled = gross - handled
             */
            if (!isset($_gross_arr[$_currency]))
            {
                $_ret_array[$_currency]['in'] = $_handled_arr[$_currency]['in'];
                $_ret_array[$_currency]['out'] = $_handled_arr[$_currency]['out'];  
            }
            else
            {
                $_ret_array[$_currency]['in'] = isset($_gross_arr[$_currency]['in']) ? 
                                                        $_gross_arr[$_currency]['in'] : 0;

                $_ret_array[$_currency]['out'] = isset($_gross_arr[$_currency]['out']) ? 
                                                        - $_gross_arr[$_currency]['out'] : 0;
            }            

        }

        return $_ret_array;

    }
    /**
     * 
     * @param  [type] $date [description]
     * @return 
     *     array (
     *         'AUD' => array('in' => 100, 'out' => 200)
     *         'USD' => array('in' => 200, 'out' => 300)
     *     )
     */
    public function handled_currency_accumulate($date_from, $date_to)
    {
        $_ret_array = array();
        if ($date_to == '')
        {
            // timezone ???
            $date_to = date('Y-m-d H:i:s');
        }

        /* 
         * currancies need to show 
         *
         * select distinct `from` from rates
         */
        $_currencies_to_show = allCurrencies();

        /**
         * handled: 某货币的累积in
         * select currency_to, sum(bank_sell_amount) acc_in,
         * from billing_detail
         * where created_at <= XXX
         * group by currency_to
         */
        $_handled_currency_in = BillingDetail::select(
                                    array(
                                            'currency_to', 
                                            DB::raw('sum(bank_sell_amount) as acc_in')))
                                ->where('created_at', '>=', $date_from)
                                ->where('created_at', '<=', $date_to)
                                ->groupBy('currency_to')
                                ->get();


        $_handled_array = array();
        foreach ($_handled_currency_in as $_h_currency)
        {
            $__key = $_h_currency->currency_to;
            $_handled_array[$__key]['in'] = $_h_currency->acc_in;
        }

        /**
         * handled: 某货币的累积out
         * select currency_from, sum(bank_buy_amount) acc_out,
         * from billing_detail
         * where created_at <= XXX
         * group by currency_from
         */
        $_handled_currency_out = BillingDetail::select(
                                    array(
                                            'currency_from', 
                                            DB::raw('sum(bank_buy_amount) as acc_out')))
                                ->where('created_at', '>=', $date_from)
                                ->where('created_at', '<=', $date_to)
                                ->groupBy('currency_from')
                                ->get();

        foreach ($_handled_currency_out as $_h_currency)
        {
            $__key = $_h_currency->currency_from;
            $_handled_array[$__key]['out'] = $_h_currency->acc_out;
        }

        /**
         * make result
         * array (
         *         'AUD' => array('in' => 100, 'out' => 200)
         *         'USD' => array('in' => 200, 'out' => 300)
         *     )
         */
        foreach ($_currencies_to_show as $_currency)
        {
            $_ret_array[$_currency]['in'] = isset($_handled_array[$_currency]['in']) ? 
                                                    $_handled_array[$_currency]['in'] : 0;

            $_ret_array[$_currency]['out'] = isset($_handled_array[$_currency]['out']) ? 
                                                   - $_handled_array[$_currency]['out'] : 0;
        }

        return $_ret_array;
    }


    public function unhandled_currency_accumulate($date_from, $date_to)
    {
        $_ret_arr = array();

        $_gross_arr   = $this->gross_currency_accumulate($date_from, $date_to);
        $_handled_arr = $this->handled_currency_accumulate($date_from, $date_to);

         /**
         * make result
         * array (
         *         'AUD' => array('in' => 100, 'out' => 200)
         *         'USD' => array('in' => 200, 'out' => 300)
         *     )
         */
        foreach ($_gross_arr as $_currency => $_billing)
        {
            /**
             * 如果用户没有换汇，即tx为空，则unhandled = 0, gross = handled
             * 如果用户有换汇，即tx非空，则 unhandled = gross - handled
             */
            if ($_gross_arr[$_currency]['in'] == $_handled_arr[$_currency]['in'] &&
                    $_gross_arr[$_currency]['out'] == $_handled_arr[$_currency]['out'])
            {
                $_ret_array[$_currency]['in'] = 0;
                $_ret_array[$_currency]['out'] = 0;        
            }
            else
            {
                $_ret_array[$_currency]['in'] = $_gross_arr[$_currency]['in'] + $_handled_arr[$_currency]['in'];
                $_ret_array[$_currency]['out'] = $_gross_arr[$_currency]['out'] + $_handled_arr[$_currency]['out'];                
            }

        }   
        
        return $_ret_array;   
    }


    /**
     * 定义 query uid：管理员筛选
     *
     * @param  Eloquent
     * @return WeXchange\Model\User
     */
    public function scopeManager($query, $uid)
    {
        if ($uid)
        {
            return $query->where('uid', '=', $uid);         
        }
        return $query;
    }

    /**
     * 定义 query scope：通过时间筛选
     *
     * @param  Eloquent
     * @param  string
     * @param  string
     * @param  string
     * @return WeXchange\Model\BillingDetail
     */
    public function scopeTimearea($query, $dw, $start_date, $expriy_date)
    {
        if ( ! $start_date AND ! $expriy_date AND $dw)
        {
            return $query->where('billings.created_at', '>', word_to_date($dw));                   
        }

        $start = urldecode($start_date);
        if ($start)
        {
            $query = $query->where('billings.created_at', '>=', time_to_search($start));
        }

        $expriy = urldecode($expriy_date);
        if ($expriy)
        {
            $query = $query->where('billings.created_at', '<=', time_to_search($expriy));
        }

        return $query;
    }

    /**
     * Get billing Details.
     *
     * @return object
     */
    public function bill_details()
    {
        return BillingDetail::where('bill_id', $this->id)->get();
    }

    /**
     * 当前billing是否有设置basic rate，即含有USDCNY或CNYUSD结算
     *
     * @return array
     */
    public function has_basic()
    {
        $details = $this->bill_details();

        $row = array();

        foreach ($details as $detail) 
        {
            // 含有USDCNY或CNYUSD结算
            if (($detail->currency_from == 'CNY' AND $detail->currency_to == 'USD') OR ($detail->currency_from == 'USD' AND $detail->currency_to == 'CNY')) 
            {
                $row[] = $detail->id;
            }
        }
        return $row;  
    } 


    /**
     * handledCurrencyAccumulate
     *     已处理的货币对的 in/out. v0.9 新 billing 算法
     *     0, 获取所有可能的货币对
     *     1, 计算 currency_from 的 in, currency_to 的 out值 (billing_detail 表)
     *     2, 计算 currency_to 的 in, currency_from 的 out值
     *     3, 合并结果，无记录的货币对的 in = out = 0
     *     
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return [array]
     * array(
     *     "AUDUSD" => array(
     *                     "AUD" => array("in" => XXX, "out" => YYY),
     *                     "USD" => array("in" => ZZZ, "out" => OOO)
     *                 ),
     *     "EURJPY" => array(
     *                     "EUR" => array("in" => AAA, "out" => CCC),
     *                     "JPY" => array("in" => BBB, "out" => DDD)
     *                 ),      
     *     ...       
     * 
     * )     * 
     */
    public function handledCurrencyAccumulate($dw, $start, $expriy)
    {

        $output = array();
        /**
         * 获取所有货币对，例如 AUDUSD, AUDCNY...
         *
         * select  `from`, `to` from rates;
         */
        $all_currencies = Rate::select(array("from", "to"))->get();

        foreach ($all_currencies as $currency)
        {
            /**
             * 
             */
            $currency_from = $currency->from;   // AUD
            $currency_to = $currency->to;       // CAD

            /**
             * 计算 currency_from(AUD) -> currency_to(CAD) 的 in, out:
             * 
             * select sum(bank_buy_amount) as `out`, sum(bank_sell_amount)  as `in`
             * from billing_detail 
             * where currency_from='AUD' and currency_to='CAD';
             * 
             */
            $from_to_acc = BillingDetail::select(
                array(
                    DB::raw('sum(bank_buy_amount) as `acc_out`'),
                    DB::raw('sum(bank_sell_amount) as `acc_in`'),
                ))
                ->timearea($dw, $start, $expriy)
                ->where('currency_from', $currency_from)
                ->where('currency_to', $currency_to)  
                ->where('status', '!=', 'Voided')
                ->first();

            /**
             * 计算 currency_to(CAD) -> currency_from(AUD) 的 in, out:
             * 
             * select sum(bank_buy_amount) as `out`, sum(bank_sell_amount)  as `in`
             * from billing_detail 
             * where currency_from='CAD' and currency_to='AUD';
             * 
             */
            $to_from_acc = BillingDetail::select(
                array(
                    DB::raw('sum(bank_buy_amount) as `acc_out`'),
                    DB::raw('sum(bank_sell_amount) as `acc_in`'),
                ))
                ->timearea($dw, $start, $expriy)
                ->where('currency_from', $currency_to)
                ->where('currency_to', $currency_from) 
                ->where('status', '!=', 'Voided')        
                ->first();

            /**
             * $output["AUDCAD"]["AUD"] = array("in" => XXX, "out" => XXX)
             * $output["AUDCAD"]["CAD"] = array("in" => XXX, "out" => XXX)
             */
            $output[$currency_from.$currency_to][$currency_from] = array(
                            "in"    => isset($to_from_acc->acc_in) ? $to_from_acc->acc_in : 0,
                            "out"   => isset($from_to_acc->acc_out) ? $from_to_acc->acc_out : 0,
                        );
            $output[$currency_from.$currency_to][$currency_to] = array(
                            "in"    => isset($from_to_acc->acc_in) ? $from_to_acc->acc_in : 0,
                            "out"   => isset($to_from_acc->acc_out) ? $to_from_acc->acc_out : 0,
                        );


        }

        return $output;
        
    }

    /**
     * grossCurrencyAccumulate
     *     货币对的 in/out 总数. v0.9 新 billing 算法
     *     0, 获取所有可能的货币对
     *     1, 计算 currency_from 的 in, currency_to 的 out值 (transactions 表)
     *     2, 计算 currency_to 的 in, currency_from 的 out值
     *     3, 合并结果，无记录的货币对的 in = handled[in], out = handled[out]
     *               
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return [array]
     * array(
     *     "AUDUSD" => array(
     *                     "AUD" => array("in" => XXX, "out" => YYY),
     *                     "USD" => array("in" => ZZZ, "out" => OOO)
     *                 ),
     *     "EURJPY" => array(
     *                     "EUR" => array("in" => AAA, "out" => CCC),
     *                     "JPY" => array("in" => BBB, "out" => DDD)
     *                 ),      
     *     ...       
     * 
     * )     * 
     */
    public function grossCurrencyAccumulate($dw, $start, $expriy)
    {

        $output = array();
        /**
         * 获取所有货币对，例如 AUDUSD, AUDCNY...
         *
         * select  `from`, `to` from rates;
         */
        $all_currencies = Rate::select(array("from", "to"))->get();

        foreach ($all_currencies as $currency)
        {
            /**
             * 
             */
            $currency_from = $currency->from;   // AUD
            $currency_to = $currency->to;       // CAD
            $currency_str = $currency_from . $currency_to;
            /**
             * 计算 currency_from(AUD) -> currency_to(CAD) 的 in, out:
             * 
             * select sum(amount) as `in`, sum(amount * rate) as `out`
             * from transactions 
             * where currency_from='AUD' and currency_to='CAD'
             *     and status = 'Completed' and type = 'Fx Deal'
             * 
             */
            $from_to_acc = Transaction::select(
                array(
                        DB::raw('sum(amount) as `acc_in`'),
                        DB::raw('sum(amount * rate) as `acc_out`')))
                ->timearea($dw, $start, $expriy)
                ->where('type', 'FX Deal')
                ->where('status', 'Completed')
                ->where('currency_from', $currency_from)
                ->where('currency_to', $currency_to)   
                ->first();

            /**
             * 计算 currency_to(CAD) -> currency_from(AUD) 的 in, out:
             * 
             * select sum(amount) as `in`, sum(amount * rate) as `out`
             * from transactions 
             * where currency_from='CAD' and currency_to='AUD'
             *     and status = 'Completed' and type = 'Fx Deal'
             * 
             */
            $to_from_acc =  Transaction::select(
                array(
                        DB::raw('sum(amount) as `acc_in`'),
                        DB::raw('sum(amount * rate) as `acc_out`')))
                ->timearea($dw, $start, $expriy)
                ->where('type', 'FX Deal')
                ->where('status', 'Completed')
                ->where('currency_from', $currency_to)
                ->where('currency_to', $currency_from)   
                ->first();

            /**
             * 如果用户今天没有交易，即transactions表中无记录，则 gross = handled        
             */
            if (!isset($from_to_acc->acc_in) && 
                    !isset($from_to_acc->acc_out) && 
                    !isset($to_from_acc->acc_in) &&
                    !isset($to_from_acc->acc_out)
                )
            {
                $handled = $this->handledCurrencyAccumulate($dw, $start, $expriy);
                $output[$currency_str][$currency_from] = $handled[$currency_str][$currency_from];                
                $output[$currency_str][$currency_to] = $handled[$currency_str][$currency_to];    

                continue;
            }
            /**
             * $output["AUDCAD"]["AUD"] = array("in" => XXX, "out" => XXX)
             * $output["AUDCAD"]["CAD"] = array("in" => XXX, "out" => XXX)
             */
            $output[$currency_str][$currency_from] = array(
                            "in"    => isset($from_to_acc->acc_in) ? $from_to_acc->acc_in : 0,
                            "out"   => isset($to_from_acc->acc_out) ? $to_from_acc->acc_out : 0,
                        );
            $output[$currency_str][$currency_to] = array(
                            "in"    => isset($to_from_acc->acc_in) ? $to_from_acc->acc_in : 0,
                            "out"   => isset($from_to_acc->acc_out) ? $from_to_acc->acc_out : 0,
                        );

        }

        return $output;
        
    }

    /**
     * unhandledCurrencyAccumulate
     *     未处理的货币对的 in/out. v0.9 新 billing 算法
     *     1,  unhandled[in] = gross[in] + handled[in]
     *         unhandled[out] = gross[out] + handled[out]
     *     
     *     2, 如果 用户没做操作，即 gross = handled, 则 
     *         unhandled[in] = unhandled[out] = 0
     *               
     * @param  string  $dw        词义日期
     * @param  string  $start     开始日期
     * @param  string  $expriy    截止日期 
     * @return [array]
     * array(
     *     "AUDUSD" => array(
     *                     "AUD" => array("in" => XXX, "out" => YYY),
     *                     "USD" => array("in" => ZZZ, "out" => OOO)
     *                 ),
     *     "EURJPY" => array(
     *                     "EUR" => array("in" => AAA, "out" => CCC),
     *                     "JPY" => array("in" => BBB, "out" => DDD)
     *                 ),      
     *     ...       
     * 
     * )     * 
     */
    public function unhandledCurrencyAccumulate($dw, $start, $expriy)
    {

        $output = array();

        $gross_arr = $this->grossCurrencyAccumulate($dw, $start, $expriy);
        $handled_arr = $this->handledCurrencyAccumulate($dw, $start, $expriy);

        foreach ($gross_arr as $currency => $billing)
        {

            $currency_from  = substr($currency, 0, 3);
            $currency_to    = substr($currency, 3, 3);
            /**
             * 
             * 
             */
            if ($billing === $handled_arr[$currency])
            {
                $output[$currency][$currency_from] = array("in" => 0, "out" => 0);
                $output[$currency][$currency_to] = array("in" => 0, "out" => 0);

                continue;
            }


            $output[$currency][$currency_from] = array(
                            "in"    => $billing[$currency_from]["in"] + $handled_arr[$currency][$currency_from]["in"],
                            "out"   => $billing[$currency_from]["out"] + $handled_arr[$currency][$currency_from]["out"],
                        );
            $output[$currency][$currency_to] = array(
                            "in"    => $billing[$currency_to]["in"] + $handled_arr[$currency][$currency_to]["in"],
                            "out"   => $billing[$currency_to]["out"] + $handled_arr[$currency][$currency_to]["out"],
                        );

        }


        return $output;
        
    }

}