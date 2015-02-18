<?php
/**
 *  汇率相关辅助函数
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * 获得自定义的汇率点数
 *
 * @param  object  货币汇率
 * @return string
 */
if ( ! function_exists('rateMaringJson'))
{
    function rateMarginJson(&$rates = NULL)
    {
        $output = array();

        if ( ! is_object($rates)) 
        {
            $rt = new Rate;
            $rates = $rt::all();
        }

        foreach ($rates as $rate)
        {
            $output[$rate->from . $rate->to] = array(
                'bidMargin'         => $rate->bid_margin / 10000,
                'askMargin'         => $rate->ask_margin / 10000,
                'silverBidMargin'   => $rate->silver_bid_margin / 10000,
                'silverAskMargin'   => $rate->silver_ask_margin / 10000,
                'goldenBidMargin'   => $rate->golden_bid_margin / 10000,
                'goldenAskMargin'   => $rate->golden_ask_margin / 10000,
                'platinumBidMargin' => $rate->platinum_bid_margin / 10000,
                'platinumAskMargin' => $rate->platinum_ask_margin / 10000,                                
            );
        }

        return json_encode($output);
    }
}

/**
 * 获取最新USDCNY汇率作为basic rate.
 *
 * @return object
 */
if ( ! function_exists('basic_rate'))
{
    function basic_rate()
    {
        $br = DB::table(DB::raw("(SELECT currency, rate, created_at FROM basic_rates UNION ALL SELECT CONCAT(currency_from,currency_to) AS currency, rate, created_at FROM billing_detail WHERE (currency_from =  'CNY' AND currency_to =  'USD') OR (currency_from =  'USD' AND currency_to =  'CNY')) as basic_rates"));

        $br = $br->orderBy('created_at', 'desc')->first();

        if ( ! $br) 
        {
            return NULL;
        }

        if ($br->currency == 'CNYUSD') 
        {
            $br->rate = round(1 / $br->rate, 4);
        }

        return $br;
    }
}

/**
 * 获取basic rate 历史.
 *
 * @param int
 * @return object
 */
if ( ! function_exists('basic_rate_history'))
{
    function basic_rate_history($nums = 10)
    {
        $br = DB::table(DB::raw("(SELECT currency, rate, created_at FROM basic_rates UNION ALL SELECT CONCAT(currency_from,currency_to) AS currency, rate, created_at FROM billing_detail WHERE (currency_from =  'CNY' AND currency_to =  'USD') OR (currency_from =  'USD' AND currency_to =  'CNY')) as basic_rates"));

        $br = $br->orderBy('created_at', 'desc')->take($nums)->get();
        
        return $br;
    }
}

/**
 * 获取指定日期的basic rate.
 *
 * @param timestamp
 * @return object
 */
if ( ! function_exists('basic_rate_search'))
{
    function basic_rate_search($date)
    {
        $s_date = Carbon::createFromTimestamp($date);

        $br = DB::table(DB::raw("(SELECT currency, rate, created_at FROM basic_rates UNION ALL SELECT CONCAT(currency_from,currency_to) AS currency, rate, created_at FROM billing_detail WHERE (currency_from =  'CNY' AND currency_to =  'USD') OR (currency_from =  'USD' AND currency_to =  'CNY')) as basic_rates"));

        $br = $br->where('created_at', '<=', $s_date)
                ->orderBy('created_at', 'desc')->first();

        // 指定日期没有汇率？获取该日期之后最近的汇率
        if ( ! $br) 
        {
            $br = DB::table(DB::raw("(SELECT currency, rate, created_at FROM basic_rates UNION ALL SELECT CONCAT(currency_from,currency_to) AS currency, rate, created_at FROM billing_detail WHERE (currency_from =  'CNY' AND currency_to =  'USD') OR (currency_from =  'USD' AND currency_to =  'CNY')) as basic_rates"));

            $br = $br->where('created_at', '>', $s_date)
                    ->orderBy('created_at', 'asc')->first();
        }

        if ( ! $br) 
        {
            return NULL;
        }

        if ($br->currency == 'CNYUSD') 
        {
            $br->rate = round(1 / $br->rate, 4);
        }

        return $br;
    }
}

/**
 * 获取 basic rate 升降.
 *
 * @return object
 */
if ( ! function_exists('basic_rate_UpDown'))
{
    function basic_rate_UpDown()
    {
        $br = basic_rate();

        if ( ! $br) 
        {
            return 0;
        }

        $last = DB::table(DB::raw("(SELECT currency, rate, created_at FROM basic_rates UNION ALL SELECT CONCAT(currency_from,currency_to) AS currency, rate, created_at FROM billing_detail WHERE (currency_from =  'CNY' AND currency_to =  'USD') OR (currency_from =  'USD' AND currency_to =  'CNY')) as basic_rates"));

        $last = $last->orderBy('created_at', 'desc')->take(1)->offset(1)->first();

        if ( ! $last) 
        {
            return 0;
        }

        if ($last->currency == 'CNYUSD') 
        {
            $last->rate = round(1 / $last->rate, 4);
        }

        if ($br->rate > $last->rate) 
        {
            return 1;
        }
        return 0;
    }
}

/**
 * 返回转账原因列表
 *
 * @return array
 */
if ( ! function_exists('transferReasons'))
{
    function transferReasons()
    {
        $reasons = array(
            '运输',
            '旅游',
            '金融保险服务',
            '专利费',
            '咨询费',
            '家庭支出',
            '投资',
            '其它经常性支出'
        );

        $reasons = array_combine($reasons, $reasons);

        $reasons = array_merge(array('' => '请选择原因'), $reasons);

        return $reasons;
    }
}

/**
 * 获取货币组的 Market Rate 
 *
 * @param  string  $currency
 * @param  string  $type
 * @return float
 */
if ( ! function_exists('currencyRate'))
{
    function currencyRate($currency, $type = 'manager', $is_bid = true)
    {
        $rs = Exchange::query($currency, $type);

        return $is_bid ? $rs[0]['Bid'] : $rs[0]['Ask'] ;
    }
}

/**
 * 获取货币组的 Actual Rate 
 *
 * @param  string  $currency      eg. AUDEUR
 * @param  string  $dw_date       词义化的时间范围
 * @param  string  $start_date    开始时间
 * @param  string  $expriy_date   截止时间
 * @return float
 */
if ( ! function_exists('currencyActualRate'))
{
    function currencyActualRate($currency, $dw, $start, $expriy, $is_bid = true)
    {
        $tran = new Transaction;

        $currency_from  = substr($currency, 0, 3);
        $currency_to = substr($currency, 3);

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
         * $output["AUDCAD"]["AUD"] = array("in" => XXX, "out" => XXX)
         * $output["AUDCAD"]["CAD"] = array("in" => XXX, "out" => XXX)
         */
        if (!isset($from_to_acc->acc_in) && 
                !isset($from_to_acc->acc_out) && 
                !isset($to_from_acc->acc_in) &&
                !isset($to_from_acc->acc_out)
            )
        {
            return 0;
        }

        $from_in  = isset($from_to_acc->acc_in) ? $from_to_acc->acc_in : 0;
        $from_out = isset($to_from_acc->acc_out) ? $to_from_acc->acc_out : 0;
        $to_in    = isset($to_from_acc->acc_in) ? $to_from_acc->acc_in : 0;
        $to_out   = isset($from_to_acc->acc_out) ? $from_to_acc->acc_out : 0;


        return round(($to_out + $to_in) / ($from_out + $from_in), 4);

    }
}
