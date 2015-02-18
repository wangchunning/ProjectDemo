<?php
/**
 *  后台用户角色相关辅助函数
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------

/**
 * 角色id转化为文字描述
 * 
 * @param  string  用｜符号连接的id
 * @return string
 */
if ( ! function_exists('id_to_string'))
{
    function id_to_string($id)
    {   
        $roles = new Role;
        $r = explode('|', $id);
        $roles_name = array();
        foreach($roles::all()->toArray() as $role)
        {
        	if (in_array($role['id'], $r)) $roles_name[] = $role['name'];
        }
        return implode(', ', $roles_name);
    }
}

/**
 * 角色权限验证
 * 
 * @param  string $action
 * @param  string $redirect  是否跳转到错误页
 * @return bool
 */
if ( ! function_exists('check_perm'))
{
    function check_perm($action, $redirect = TRUE)
    {           
        $manager = login_user(Administrator::LABEL);

        if( ! $redirect )
        {
           return $manager->can($action);
        }

        if ( ! $manager->can($action)) 
        {
            Session::flash('perm_error', 'You don\'t have the permissions!');
            // 跳转到没有权限的错误页面
            App::error(function($exception)
            {
                return Response::view('errors.unauthorize');
            });
            return App::abort('401');       
        }
        return TRUE;    
    }
}

/**
 * 角色组合权限验证
 * 
 * @param  string|array $actions 字符类型以逗号隔开: add_role,invite_user
 * @param  string $type AND|OR
 * @param  string $redirect  是否跳转到错误页
 * @return bool
 */
if ( ! function_exists('check_perms'))
{
    function check_perms($actions, $type = 'AND', $redirect = TRUE)
    {
        $manager = login_user(Administrator::LABEL);

        $options = array(
            'validate_all' => $type == 'AND' ? TRUE : FALSE
        );

        if( ! $redirect )
        {
           return $manager->ability($actions, $options);
        }

        if ( ! $manager->ability($actions, $options)) 
        {
            Session::flash('perm_error', 'You don\'t have the permissions!');
            // 跳转到没有权限的错误页面
            App::error(function($exception)
            {
                return Response::view('errors.unauthorize');
            });
            return App::abort('401'); 
        }
        return TRUE; 
    }
}

/**
 * Timezone Menu
 *
 * Generates a drop-down menu of timezones.
 *
 * @access  public
 * @param   string  timezone
 * @param   string  classname
 * @param   string  menu name
 * @return  string
 */
if ( ! function_exists('timezone_menu'))
{
    function timezone_menu($default = 'UTC', $class = "", $name = 'timezone')
    {
        if ($default == 'GMT')
            $default = 'UTC';

        $menu = '<select name="'.$name.'"';

        if ($class != '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu .= ">\n";

        foreach (timezones() as $key => $val)
        {
            $selected = ($default == $key) ? " selected='selected'" : '';
            $menu .= "<option value='{$key}'{$selected}>".$val."</option>\n";
        }

        $menu .= "</select>";

        return $menu;
    }
}

// ------------------------------------------------------------------------

/**
 * Timezones
 *
 * Returns an array of timezones.  This is a helper function
 * for various other ones in this library
 *
 * @access  public
 * @param   string  timezone
 * @return  string
 */
if ( ! function_exists('timezones'))
{
    function timezones($tz = '')
    {
        // Note: Don't change the order of these even though
        // some items appear to be in the wrong order

        $zones = array(
                        'Pacific/Midway' => '(GMT-11:00) Midway',
                        'Pacific/Niue' => '(GMT-11:00) Niue',
                        'Pacific/Pago_Pago' => '(GMT-11:00) Pago Pago',
                        'Pacific/Honolulu' => '(GMT-10:00) Hawaii Time',
                        'Pacific/Rarotonga' => '(GMT-10:00) Rarotonga',
                        'Pacific/Tahiti' => '(GMT-10:00) Tahiti',
                        'Pacific/Marquesas' => '(GMT-09:30) Marquesas',
                        'America/Anchorage' => '(GMT-09:00) Alaska Time',
                        'Pacific/Gambier' => '(GMT-09:00) Gambier',
                        'America/Los_Angeles' => '(GMT-08:00) Pacific Time',
                        'America/Tijuana' => '(GMT-08:00) Pacific Time - Tijuana',
                        'America/Vancouver' => '(GMT-08:00) Pacific Time - Vancouver',
                        'America/Whitehorse' => '(GMT-08:00) Pacific Time - Whitehorse',
                        'Pacific/Pitcairn' => '(GMT-08:00) Pitcairn',
                        'America/Dawson_Creek' => '(GMT-07:00) Mountain Time - Dawson Creek',
                        'America/Denver' => '(GMT-07:00) Mountain Time',
                        'America/Edmonton' => '(GMT-07:00) Mountain Time - Edmonton',
                        'America/Hermosillo' => '(GMT-07:00) Mountain Time - Hermosillo',
                        'America/Mazatlan' => '(GMT-07:00) Mountain Time - Chihuahua, Mazatlan',
                        'America/Phoenix' => '(GMT-07:00) Mountain Time - Arizona',
                        'America/Yellowknife' => '(GMT-07:00) Mountain Time - Yellowknife',
                        'America/Belize' => '(GMT-06:00) Belize',
                        'America/Chicago' => '(GMT-06:00) Central Time',
                        'America/Costa_Rica' => '(GMT-06:00) Costa Rica',
                        'America/El_Salvador' => '(GMT-06:00) El Salvador',
                        'America/Guatemala' => '(GMT-06:00) Guatemala',
                        'America/Managua' => '(GMT-06:00) Managua',
                        'America/Mexico_City' => '(GMT-06:00) Central Time - Mexico City',
                        'America/Regina' => '(GMT-06:00) Central Time - Regina',
                        'America/Tegucigalpa' => '(GMT-06:00) Central Time - Tegucigalpa',
                        'America/Winnipeg' => '(GMT-06:00) Central Time - Winnipeg',
                        'Pacific/Easter' => '(GMT-06:00) Easter Island',
                        'Pacific/Galapagos' => '(GMT-06:00) Galapagos',
                        'America/Bogota' => '(GMT-05:00) Bogota',
                        'America/Cayman' => '(GMT-05:00) Cayman',
                        'America/Grand_Turk' => '(GMT-05:00) Grand Turk',
                        'America/Guayaquil' => '(GMT-05:00) Guayaquil',
                        'America/Havana' => '(GMT-05:00) Havana',
                        'America/Iqaluit' => '(GMT-05:00) Eastern Time - Iqaluit',
                        'America/Jamaica' => '(GMT-05:00) Jamaica',
                        'America/Lima' => '(GMT-05:00) Lima',
                        'America/Montreal' => '(GMT-05:00) Eastern Time - Montreal',
                        'America/Nassau' => '(GMT-05:00) Nassau',
                        'America/New_York' => '(GMT-05:00) Eastern Time',
                        'America/Panama' => '(GMT-05:00) Panama',
                        'America/Port-au-Prince' => '(GMT-05:00) Port-au-Prince',
                        'America/Toronto' => '(GMT-05:00) Eastern Time - Toronto',
                        'America/Caracas' => '(GMT-04:30) Caracas',
                        'America/Antigua' => '(GMT-04:00) Antigua',
                        'America/Asuncion' => '(GMT-04:00) Asuncion',
                        'America/Barbados' => '(GMT-04:00) Barbados',
                        'America/Boa_Vista' => '(GMT-04:00) Boa Vista',
                        'America/Campo_Grande' => '(GMT-04:00) Campo Grande',
                        'America/Cuiaba' => '(GMT-04:00) Cuiaba',
                        'America/Curacao' => '(GMT-04:00) Curacao',
                        'America/Guyana' => '(GMT-04:00) Guyana',
                        'America/Halifax' => '(GMT-04:00) Atlantic Time - Halifax',
                        'America/La_Paz' => '(GMT-04:00) La Paz',
                        'America/Manaus' => '(GMT-04:00) Manaus',
                        'America/Martinique' => '(GMT-04:00) Martinique',
                        'America/Port_of_Spain' => '(GMT-04:00) Port of Spain',
                        'America/Porto_Velho' => '(GMT-04:00) Porto Velho',
                        'America/Puerto_Rico' => '(GMT-04:00) Puerto Rico',
                        'America/Rio_Branco' => '(GMT-04:00) Rio Branco',
                        'America/Santiago' => '(GMT-04:00) Santiago',
                        'America/Santo_Domingo' => '(GMT-04:00) Santo Domingo',
                        'America/Thule' => '(GMT-04:00) Thule',
                        'Antarctica/Palmer' => '(GMT-04:00) Palmer',
                        'Atlantic/Bermuda' => '(GMT-04:00) Bermuda',
                        'America/St_Johns' => '(GMT-03:30) Newfoundland Time - St. Johns',
                        'America/Araguaina' => '(GMT-03:00) Araguaina',
                        'America/Argentina/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
                        'America/Bahia' => '(GMT-03:00) Salvador',
                        'America/Belem' => '(GMT-03:00) Belem',
                        'America/Cayenne' => '(GMT-03:00) Cayenne',
                        'America/Fortaleza' => '(GMT-03:00) Fortaleza',
                        'America/Godthab' => '(GMT-03:00) Godthab',
                        'America/Maceio' => '(GMT-03:00) Maceio',
                        'America/Miquelon' => '(GMT-03:00) Miquelon',
                        'America/Montevideo' => '(GMT-03:00) Montevideo',
                        'America/Paramaribo' => '(GMT-03:00) Paramaribo',
                        'America/Recife' => '(GMT-03:00) Recife',
                        'America/Sao_Paulo' => '(GMT-03:00) Sao Paulo',
                        'Antarctica/Rothera' => '(GMT-03:00) Rothera',
                        'Atlantic/Stanley' => '(GMT-03:00) Stanley',
                        'America/Noronha' => '(GMT-02:00) Noronha',
                        'Atlantic/South_Georgia' => '(GMT-02:00) South Georgia',
                        'America/Scoresbysund' => '(GMT-01:00) Scoresbysund',
                        'Atlantic/Azores' => '(GMT-01:00) Azores',
                        'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde',
                        'Africa/Abidjan' => '(GMT+00:00) Abidjan',
                        'Africa/Accra' => '(GMT+00:00) Accra',
                        'Africa/Bamako' => '(GMT+00:00) Bamako',
                        'Africa/Banjul' => '(GMT+00:00) Banjul',
                        'Africa/Bissau' => '(GMT+00:00) Bissau',
                        'Africa/Casablanca' => '(GMT+00:00) Casablanca',
                        'Africa/Conakry' => '(GMT+00:00) Conakry',
                        'Africa/Dakar' => '(GMT+00:00) Dakar',
                        'Africa/El_Aaiun' => '(GMT+00:00) El Aaiun',
                        'Africa/Freetown' => '(GMT+00:00) Freetown',
                        'Africa/Lome' => '(GMT+00:00) Lome',
                        'Africa/Monrovia' => '(GMT+00:00) Monrovia',
                        'Africa/Nouakchott' => '(GMT+00:00) Nouakchott',
                        'Africa/Ouagadougou' => '(GMT+00:00) Ouagadougou',
                        'Africa/Sao_Tome' => '(GMT+00:00) Sao Tome',
                        'America/Danmarkshavn' => '(GMT+00:00) Danmarkshavn',
                        'Atlantic/Canary' => '(GMT+00:00) Canary Islands',
                        'Atlantic/Faroe' => '(GMT+00:00) Faeroe',
                        'Atlantic/Reykjavik' => '(GMT+00:00) Reykjavik',
                        'Atlantic/St_Helena' => '(GMT+00:00) St Helena',
                        'Etc/GMT' => '(GMT+00:00) GMT (no daylight saving)',
                        'Europe/Dublin' => '(GMT+00:00) Dublin',
                        'Europe/Lisbon' => '(GMT+00:00) Lisbon',
                        'Europe/London' => '(GMT+00:00) London',
                        'Africa/Algiers' => '(GMT+01:00) Algiers',
                        'Africa/Bangui' => '(GMT+01:00) Bangui',
                        'Africa/Brazzaville' => '(GMT+01:00) Brazzaville',
                        'Africa/Ceuta' => '(GMT+01:00) Ceuta',
                        'Africa/Douala' => '(GMT+01:00) Douala',
                        'Africa/Kinshasa' => '(GMT+01:00) Kinshasa',
                        'Africa/Lagos' => '(GMT+01:00) Lagos',
                        'Africa/Libreville' => '(GMT+01:00) Libreville',
                        'Africa/Luanda' => '(GMT+01:00) Luanda',
                        'Africa/Malabo' => '(GMT+01:00) Malabo',
                        'Africa/Ndjamena' => '(GMT+01:00) Ndjamena',
                        'Africa/Niamey' => '(GMT+01:00) Niamey',
                        'Africa/Porto-Novo' => '(GMT+01:00) Porto-Novo',
                        'Africa/Tripoli' => '(GMT+01:00) Tripoli',
                        'Africa/Tunis' => '(GMT+01:00) Tunis',
                        'Africa/Windhoek' => '(GMT+01:00) Windhoek',
                        'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
                        'Europe/Andorra' => '(GMT+01:00) Andorra',
                        'Europe/Belgrade' => '(GMT+01:00) Central European Time - Belgrade',
                        'Europe/Berlin' => '(GMT+01:00) Berlin',
                        'Europe/Brussels' => '(GMT+01:00) Brussels',
                        'Europe/Budapest' => '(GMT+01:00) Budapest',
                        'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
                        'Europe/Gibraltar' => '(GMT+01:00) Gibraltar',
                        'Europe/Luxembourg' => '(GMT+01:00) Luxembourg',
                        'Europe/Madrid' => '(GMT+01:00) Madrid',
                        'Europe/Malta' => '(GMT+01:00) Malta',
                        'Europe/Monaco' => '(GMT+01:00) Monaco',
                        'Europe/Oslo' => '(GMT+01:00) Oslo',
                        'Europe/Paris' => '(GMT+01:00) Paris',
                        'Europe/Prague' => '(GMT+01:00) Central European Time - Prague',
                        'Europe/Rome' => '(GMT+01:00) Rome',
                        'Europe/Stockholm' => '(GMT+01:00) Stockholm',
                        'Europe/Tirane' => '(GMT+01:00) Tirane',
                        'Europe/Vienna' => '(GMT+01:00) Vienna',
                        'Europe/Warsaw' => '(GMT+01:00) Warsaw',
                        'Europe/Zurich' => '(GMT+01:00) Zurich',
                        'Africa/Blantyre' => '(GMT+02:00) Blantyre',
                        'Africa/Bujumbura' => '(GMT+02:00) Bujumbura',
                        'Africa/Cairo' => '(GMT+02:00) Cairo',
                        'Africa/Gaborone' => '(GMT+02:00) Gaborone',
                        'Africa/Harare' => '(GMT+02:00) Harare',
                        'Africa/Johannesburg' => '(GMT+02:00) Johannesburg',
                        'Africa/Kigali' => '(GMT+02:00) Kigali',
                        'Africa/Lubumbashi' => '(GMT+02:00) Lubumbashi',
                        'Africa/Lusaka' => '(GMT+02:00) Lusaka',
                        'Africa/Maputo' => '(GMT+02:00) Maputo',
                        'Africa/Maseru' => '(GMT+02:00) Maseru',
                        'Africa/Mbabane' => '(GMT+02:00) Mbabane',
                        'Asia/Beirut' => '(GMT+02:00) Beirut',
                        'Asia/Damascus' => '(GMT+02:00) Damascus',
                        'Asia/Gaza' => '(GMT+02:00) Gaza',
                        'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
                        'Asia/Nicosia' => '(GMT+02:00) Nicosia',
                        'Europe/Athens' => '(GMT+02:00) Athens',
                        'Europe/Bucharest' => '(GMT+02:00) Bucharest',
                        'Europe/Chisinau' => '(GMT+02:00) Chisinau',
                        'Europe/Helsinki' => '(GMT+02:00) Helsinki',
                        'Europe/Istanbul' => '(GMT+02:00) Istanbul',
                        'Europe/Kiev' => '(GMT+02:00) Kiev',
                        'Europe/Riga' => '(GMT+02:00) Riga',
                        'Europe/Sofia' => '(GMT+02:00) Sofia',
                        'Europe/Tallinn' => '(GMT+02:00) Tallinn',
                        'Europe/Vilnius' => '(GMT+02:00) Vilnius',
                        'Africa/Addis_Ababa' => '(GMT+03:00) Addis Ababa',
                        'Africa/Asmara' => '(GMT+03:00) Asmera',
                        'Africa/Dar_es_Salaam' => '(GMT+03:00) Dar es Salaam',
                        'Africa/Djibouti' => '(GMT+03:00) Djibouti',
                        'Africa/Kampala' => '(GMT+03:00) Kampala',
                        'Africa/Khartoum' => '(GMT+03:00) Khartoum',
                        'Africa/Mogadishu' => '(GMT+03:00) Mogadishu',
                        'Africa/Nairobi' => '(GMT+03:00) Nairobi',
                        'Antarctica/Syowa' => '(GMT+03:00) Syowa',
                        'Asia/Aden' => '(GMT+03:00) Aden',
                        'Asia/Amman' => '(GMT+03:00) Amman',
                        'Asia/Baghdad' => '(GMT+03:00) Baghdad',
                        'Asia/Bahrain' => '(GMT+03:00) Bahrain',
                        'Asia/Kuwait' => '(GMT+03:00) Kuwait',
                        'Asia/Qatar' => '(GMT+03:00) Qatar',
                        'Asia/Riyadh' => '(GMT+03:00) Riyadh',
                        'Europe/Kaliningrad' => '(GMT+03:00) Moscow-01 - Kaliningrad',
                        'Europe/Minsk' => '(GMT+03:00) Minsk',
                        'Indian/Antananarivo' => '(GMT+03:00) Antananarivo',
                        'Indian/Comoro' => '(GMT+03:00) Comoro',
                        'Indian/Mayotte' => '(GMT+03:00) Mayotte',
                        'Asia/Tehran' => '(GMT+03:30) Tehran',
                        'Asia/Baku' => '(GMT+04:00) Baku',
                        'Asia/Dubai' => '(GMT+04:00) Dubai',
                        'Asia/Muscat' => '(GMT+04:00) Muscat',
                        'Asia/Tbilisi' => '(GMT+04:00) Tbilisi',
                        'Asia/Yerevan' => '(GMT+04:00) Yerevan',
                        'Europe/Moscow' => '(GMT+04:00) Moscow+00',
                        'Europe/Samara' => '(GMT+04:00) Moscow+00 - Samara',
                        'Indian/Mahe' => '(GMT+04:00) Mahe',
                        'Indian/Mauritius' => '(GMT+04:00) Mauritius',
                        'Indian/Reunion' => '(GMT+04:00) Reunion',
                        'Asia/Kabul' => '(GMT+04:30) Kabul',
                        'Antarctica/Mawson' => '(GMT+05:00) Mawson',
                        'Asia/Aqtau' => '(GMT+05:00) Aqtau',
                        'Asia/Aqtobe' => '(GMT+05:00) Aqtobe',
                        'Asia/Ashgabat' => '(GMT+05:00) Ashgabat',
                        'Asia/Dushanbe' => '(GMT+05:00) Dushanbe',
                        'Asia/Karachi' => '(GMT+05:00) Karachi',
                        'Asia/Tashkent' => '(GMT+05:00) Tashkent',
                        'Indian/Kerguelen' => '(GMT+05:00) Kerguelen',
                        'Indian/Maldives' => '(GMT+05:00) Maldives',
                        'Asia/Calcutta' => '(GMT+05:30) India Standard Time',
                        'Asia/Colombo' => '(GMT+05:30) Colombo',
                        'Asia/Katmandu' => '(GMT+05:45) Katmandu',
                        'Antarctica/Vostok' => '(GMT+06:00) Vostok',
                        'Asia/Almaty' => '(GMT+06:00) Almaty',
                        'Asia/Bishkek' => '(GMT+06:00) Bishkek',
                        'Asia/Dhaka' => '(GMT+06:00) Dhaka',
                        'Asia/Thimphu' => '(GMT+06:00) Thimphu',
                        'Asia/Yekaterinburg' => '(GMT+06:00) Moscow+02 - Yekaterinburg',
                        'Indian/Chagos' => '(GMT+06:00) Chagos',
                        'Asia/Rangoon' => '(GMT+06:30) Rangoon',
                        'Indian/Cocos' => '(GMT+06:30) Cocos',
                        'Antarctica/Davis' => '(GMT+07:00) Davis',
                        'Asia/Bangkok' => '(GMT+07:00) Bangkok',
                        'Asia/Hovd' => '(GMT+07:00) Hovd',
                        'Asia/Jakarta' => '(GMT+07:00) Jakarta',
                        'Asia/Omsk' => '(GMT+07:00) Moscow+03 - Omsk, Novosibirsk',
                        'Asia/Phnom_Penh' => '(GMT+07:00) Phnom Penh',
                        'Asia/Saigon' => '(GMT+07:00) Hanoi',
                        'Asia/Vientiane' => '(GMT+07:00) Vientiane',
                        'Indian/Christmas' => '(GMT+07:00) Christmas',
                        'Antarctica/Casey' => '(GMT+08:00) Casey',
                        'Asia/Brunei' => '(GMT+08:00) Brunei',
                        'Asia/Choibalsan' => '(GMT+08:00) Choibalsan',
                        'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
                        'Asia/Krasnoyarsk' => '(GMT+08:00) Moscow+04 - Krasnoyarsk',
                        'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
                        'Asia/Macau' => '(GMT+08:00) Macau',
                        'Asia/Makassar' => '(GMT+08:00) Makassar',
                        'Asia/Manila' => '(GMT+08:00) Manila',
                        'Asia/Shanghai' => '(GMT+08:00) China Time - Beijing',
                        'Asia/Singapore' => '(GMT+08:00) Singapore',
                        'Asia/Taipei' => '(GMT+08:00) Taipei',
                        'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaanbaatar',
                        'Australia/Perth' => '(GMT+08:00) Western Time - Perth',
                        'Asia/Dili' => '(GMT+09:00) Dili',
                        'Asia/Irkutsk' => '(GMT+09:00) Moscow+05 - Irkutsk',
                        'Asia/Jayapura' => '(GMT+09:00) Jayapura',
                        'Asia/Pyongyang' => '(GMT+09:00) Pyongyang',
                        'Asia/Seoul' => '(GMT+09:00) Seoul',
                        'Asia/Tokyo' => '(GMT+09:00) Tokyo',
                        'Pacific/Palau' => '(GMT+09:00) Palau',
                        'Australia/Adelaide' => '(GMT+09:30) Central Time - Adelaide',
                        'Australia/Darwin' => '(GMT+09:30) Central Time - Darwin',
                        'Antarctica/DumontDUrville' => '(GMT+10:00) Dumont D\'Urville',
                        'Asia/Yakutsk' => '(GMT+10:00) Moscow+06 - Yakutsk',
                        'Australia/Brisbane' => '(GMT+10:00) Eastern Time - Brisbane',
                        'Australia/Canberra' => '(GMT+10:00) Eastern Time - Canberra',
                        'Australia/Hobart' => '(GMT+10:00) Eastern Time - Hobart',
                        'Australia/Sydney' => '(GMT+10:00) Eastern Time - Sydney',
                        'Australia/Melbourne' => '(GMT+10:00) Eastern Time - Melbourne',
                        'Pacific/Chuuk' => '(GMT+10:00) Truk',
                        'Pacific/Guam' => '(GMT+10:00) Guam',
                        'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
                        'Pacific/Saipan' => '(GMT+10:00) Saipan',
                        'Asia/Vladivostok' => '(GMT+11:00) Moscow+07 - Yuzhno-Sakhalinsk',
                        'Pacific/Efate' => '(GMT+11:00) Efate',
                        'Pacific/Guadalcanal' => '(GMT+11:00) Guadalcanal',
                        'Pacific/Kosrae' => '(GMT+11:00) Kosrae',
                        'Pacific/Noumea' => '(GMT+11:00) Noumea',
                        'Pacific/Pohnpei' => '(GMT+11:00) Ponape',
                        'Pacific/Norfolk' => '(GMT+11:30) Norfolk',
                        'Asia/Kamchatka' => '(GMT+12:00) Moscow+08 - Petropavlovsk-Kamchatskiy',
                        'Asia/Magadan' => '(GMT+12:00) Moscow+08 - Magadan',
                        'Pacific/Auckland' => '(GMT+12:00) Auckland',
                        'Pacific/Fiji' => '(GMT+12:00) Fiji',
                        'Pacific/Funafuti' => '(GMT+12:00) Funafuti',
                        'Pacific/Kwajalein' => '(GMT+12:00) Kwajalein',
                        'Pacific/Majuro' => '(GMT+12:00) Majuro',
                        'Pacific/Nauru' => '(GMT+12:00) Nauru',
                        'Pacific/Tarawa' => '(GMT+12:00) Tarawa',
                        'Pacific/Wake' => '(GMT+12:00) Wake',
                        'Pacific/Wallis' => '(GMT+12:00) Wallis',
                        'Pacific/Apia' => '(GMT+13:00) Apia',
                        'Pacific/Enderbury' => '(GMT+13:00) Enderbury',
                        'Pacific/Fakaofo' => '(GMT+13:00) Fakaofo',
                        'Pacific/Tongatapu' => '(GMT+13:00) Tongatapu',
                        'Pacific/Kiritimati' => '(GMT+14:00) Kiritimati'
                    );

        if ($tz == '')
        {
            return $zones;
        }

        if ($tz == 'GMT')
            $tz = 'UTC';

        return ( ! isset($zones[$tz])) ? 0 : $zones[$tz];
    }
}