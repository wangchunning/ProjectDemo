<?php
/**
 *  Billing 相关辅助函数
 *
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * 添加到 Record Deal
 *
 * @param  string  $currency  eg. AUDCAD
 * @return void
 */
if ( ! function_exists('addToRecords'))
{
    function addToRecords($currency)
    {
        if ( ! $currency)
        {
            return;
        }

        $currencies = Session::get('record_currencies');

        $currencies = unserialize($currencies);

        $currencies[] = $currency;

        $currencies = array_flip(array_flip($currencies));

        Session::put('record_currencies', serialize($currencies));
    }
}

/**
 * Records 中是否存在指定货币
 *
 * @param  string  $currency  eg. AUDCAD
 * @return bool
 */
if ( ! function_exists('recordsHas'))
{
    function recordsHas($currency)
    {
        if ( ! $currency)
        {
            return;
        }

        if ( ! $currencies = Session::get('record_currencies'))
        {
            return false;
        }

        $currencies = unserialize($currencies);

        return in_array($currency, $currencies);
    }
}

/**
 * 从 Records 移除指定货币
 *
 * @param  string  $currency  eg. AUDCAD
 * @return void
 */
if ( ! function_exists('recordsRemove'))
{
    function recordsRemove($currency)
    {
        if ( ! $currency)
        {
            return;
        }

        if ( ! $currencies = Session::get('record_currencies'))
        {
            return false;
        }

        $currencies = unserialize($currencies);

        if (($key = array_search($currency, $currencies)) !== false)
        {
            unset($currencies[$key]);
        }        

        Session::put('record_currencies', serialize($currencies));
    }
}

/**
 * 清空 Records
 *
 * @return void
 */
if ( ! function_exists('clearRecords'))
{
    function clearRecords()
    {
        Session::forget('record_currencies');
    }
}

/**
 * 加入 Records 的货币组
 *
 * @return array
 */
if ( ! function_exists('recordCurrencies'))
{
    function recordCurrencies()
    {
        if ( ! $currencies =  unserialize(Session::get('record_currencies')))
        {
            $currencies = array();
        }

        return $currencies;
    }
}


/**
 * 生成 billing 流水号
 *
 *
 * @param  string
 * @return string
 */
if ( ! function_exists('bn'))
{
    function bn()
    {
        $file = 'uploads/billing.dat';

        if ($id = @file_get_contents($file))
        {
            $id++;
        }
        else
        {
            $id = Date('Ymd', Carbon::today()->timestamp) . '0001';
        }
        
        $fp = fopen($file, 'w+');

        flock($fp, LOCK_EX);

        fwrite($fp, $id);

        flock($fp, LOCK_UN);

        return  'B' . $id;
    }
}