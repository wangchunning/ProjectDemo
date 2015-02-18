<?php
/**
 *  日期相关辅助函数
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * UTC时间转换指定时区时间
 * 
 * @param  string  $string_date
 * @param  string  $tz
 * @return string
 */
if ( ! function_exists('time_to_tz'))
{
    function time_to_tz($string_date, $tz = NULL)
    {
    	if ( ! $tz )
        {
            if (is_login('admin')) 
            {
                $tz = Auth::admin()->user()->timezone;
            }
            else if (is_login())
            {
                $user = real_user();
                $tz = $user->timezone;
            }	
    	}  
    	$ts = strtotime($string_date);

		/*
		 * datetime format: 16 Dec 2013 04:07
		 */		
    	$dt = Carbon::createFromTimestamp($ts, $tz);

    	$dt_format = 'd M Y H:i';
    	$dt->setToStringFormat($dt_format);
    	
    	return '' . $dt;
    }
}

/**
* 词义化时间
*
* @access public
* @param string $from 起始时间
* @param string $now 终止时间
* @return string
    */
if (! function_exists('date_word'))
{
    function date_word($from, $now = NULL, $tz = NULL)
    {       
    	if ( ! $now) $now = time();

    	if ( ! is_int($from)) $from = strtotime((string)$from);

    	if (is_string($now)) $now = strtotime($now);
    	
        if ( ! $tz )
        {
            if (is_login('admin')) 
            {
                $tz = Auth::admin()->user()->timezone;
            }
            else if (is_login())
            {
                $user = real_user();
                $tz = $user->timezone;
            }   
        }

        $dt_from = Carbon::createFromTimestamp($from, $tz);

        // Distance in seconds
        $s = round(abs($now - $from));
        // Distance in minutes
        $m = round($s / 60);

        if($m <= 1)
        {
            if ( $s < 5 )
            {
                return 'just_now';
            }
            if ( $s < 60 )
            {
                return sprintf('%s seconds ago', $s);
            }

            return '1 minute ago';
        }

        if ( $m < 60 )
        {
            return sprintf('%s minutes ago', $m);
        }

        if ( $m < 120 )
        {
            return 'an hour ago';
        }

        if ( $m < 1440 )
        {
            return sprintf('%s hours ago', round(floatval($m) / 60.0));
        }

        if ( $m < 2880 )
        {
            return 'a day ago';
        }

        /*
         * 2天后（精确到年月日）
         * datetime format: 16 Dec 2013 04:07
         */
        if ( $m > 24 * 60 * 2 )
        {
        	$dt_format = 'd M Y H:i';
        	$dt_from->setToStringFormat($dt_format);
        	return '' . $dt_from;
        }

        /*
        if ( $m < 43200 )
        {
            return sprintf('%s days ago', round(floatval($m) / 1440));
        }

        
        if ( $m < 86400 ) {
            return 'a month ago';
        }

        if ( $m < 525600 ) {
            return sprintf('%s months ago', round(floatval($m) / 43200));
        }

        if ( $m < 1051199 ) {
            return 'a year ago';
        }

        return sprintf('%s years ago', round(floatval($m) / 525600));
        */
    }
}

/**
* 时间转化为数据库可查询形式
*
* @access public
* @param string $time 时间
* @param string $format
* @return string
    */
if (! function_exists('time_to_search'))
{
    function time_to_search($time, $format = 'm/d/Y')
    {
        if (! $time)
        {
            return Carbon::now();
        }

        return $dt = Carbon::createFromFormat($format, $time);
    }
}

/**
 * 常用查询日期
 *
 * @return array
 */
if ( ! function_exists('commonDate'))
{
    function commonDate($key = NULL)
    {
        $dates = array(
            'today'         => '今天',
            'thisWeek'      => '本周内',
            'lastWeek'      => '最近 2 周',
            'thisMonth'     => '本月内',
            'lastMonth'     => '最近 2 月',
            'last3Month'    => '最近 3 月'
        );

        if (is_null($key))
        {
            return $dates;
        }

        return isset($dates[$key]) ? $dates[$key] : 'All Date';
    }
}

/**
* 常用日期转换
*
* @param string $word 词义时间
* @return string
    */
if (! function_exists('word_to_date'))
{
    function word_to_date($word)
    {
        switch ($word) {
            case 'today':
                $dt = Carbon::now()->today();
                break;

            case 'thisWeek':
                $dt = Carbon::now()->subWeek();
                break;

            case 'lastWeek':
                $dt = Carbon::now()->subWeeks(2);
                break;

            case 'thisMonth':
                $dt = Carbon::now()->subMonth();
                break;

            case 'lastMonth':
                $dt = Carbon::now()->subMonths(2);
                break;

            case 'last3Month':
                $dt = Carbon::now()->subMonths(3);
                break;

            default:
                $dt = Carbon::now()->subWeek();
        }
        
        return $dt;
    }
}

/**
 * 常用查询日期
 *
 * @return array
 */
if ( ! function_exists('commonDate2word'))
{
    function commonDate2word($key = NULL)
    {

        $dates = array(
            '今天'          => 'today',
            '本周内'      => 'thisWeek',
            '最近 2 周'      => 'lastWeek',
            '本月内'     => 'thisMonth',
            '最近 2 月'     => 'lastMonth',
            '最近 3 月'   => 'last3Month'
        );

        if (is_null($key))
        {
            return 'today';
        }

        return isset($dates[$key]) ? $dates[$key] : 'All Date';
    }
}
