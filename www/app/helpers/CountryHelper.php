<?php
/**
 *  国家相关辅助函数
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------


/**
 * 获取收款人信息中的可选国家
 *
 * @return array 
 */
/*
if ( ! function_exists('countries'))
{
    function countries()
    {
        $alias = array(
            'AUD' => array('Australia'),
            'CAD' => array('Canada'),
            'CNY' => array('China'),
            'EUR' => array('Ireland','Austria','Belgium','Germany','France','Finland',
                    'Holland','Luxembourg','Portuguesa','Spain','Greece','Italy'),
            'GBP' => array('United Kingdom'),            
            'HKD' => array('Hong Kong'),
            'JPY' => array('Japan'),
            'NZD' => array('New Zealand'),
            'SGD' => array('Singapore'),
            'USD' => array('United States')
        );

        $alias = array_flatten($alias);
        sort($alias);

        return array_combine($alias, $alias);
    }
}
*/

/**
 * 取得所有国家
 *
 * @param  bool   是否只输出国家名称
 * @return array 
 */
if ( ! function_exists('countries'))
{
    function countries($onlyName = false)
    {
        $countries = array(
            'Afghanistan' => '93',
            'Albania' => '355',
            'Algeria' => '213',
            'American Samoa' => '1684',
            'Andorra' => '376',
            'Angola' => '244',
            'Anguilla' => '1264',
            'Antarctica' => '672',
            'Antigua and Barbuda' => '1268',
            'Antilles, Netherlands' => '599',
            'Argentina' => '54',
            'Armenia' => '374',
            'Aruba' => '297',
            'Australia' => '61',
            'Austria' => '43',
            'Azerbaijan' => '994',
            'Bahamas' => '1242',
            'Bahrain' => '973',
            'Bangladesh' => '880',
            'Barbados' => '1246',
            'Belarus' => '375',
            'Belgium' => '375',
            'Belize' => '501',
            'Benin' => '229',
            'Bermuda' => '1441',
            'Bhutan' => '975',
            'Bolivia' => '591',
            'Bosnia and Herzegovina' => '387',
            'Botswana' => '267',
            'Brazil' => '55',
            'British Indian Ocean Territory' => '246',
            'British Virgin Islands' => '1284',
            'Brunei Darussalam' => '673',
            'Bulgaria' => '359',
            'Burkina Faso' => '226',
            'Burundi' => '257',
            'Cambodia' => '855',
            'Cameroon' => '237',
            'Canada' => '1',
            'Cape Verde' => '238',
            'Cayman Islands' => '1345',
            'Central African Republic' => '236',
            'Chad' => '235',
            'Chile' => '56',
            'China' => '86',
            'Christmas Island' => '64',
            //'Cocos (Keeling) Islands' => '61',
            'Colombia' => '57',
            'Comoros' => '269',
            'Congo' => '242',
            'Cook Islands' => '682',
            'Costa Rica' => '506',
            'Cote D\'Ivoire' => '225',
            'Croatia' => '385',
            'Cuba' => '53',
            'Cyprus' => '357',
            'Czech Republic' => '420',
            'Denmark' => '45',
            'Djibouti' => '253',
            'Dominica' => '1767',
            'Dominican Republic' => '1809',
            'East Timor (Timor-Leste)' => '670',
            'Ecuador' => '593',
            'Egypt' => '20',
            'El Salvador' => '503',
            'Equatorial Guinea' => '240',
            'Eritrea' => '291',
            'Estonia' => '372',
            'Ethiopia' => '251',
            'Falkland Islands (Malvinas)' => '500',
            'Faroe Islands' => '298',
            'Fiji' => '679',
            'Finland' => '358',
            'France' => '33',
            'French Guiana' => '594',
            'French Polynesia' => '689',
            'Gabon' => '241',
            'Gambia, the' => '220',
            'Georgia' => '995',
            'Germany' => '49',
            'Ghana' => '233',
            'Gibraltar' => '350',
            'Greece' => '30',
            'Greenland' => '299',
            'Grenada' => '1 473',
            'Guadeloupe' => '590',
            'Guam' => '1 671',
            'Guatemala' => '502',
            'Guernsey and Alderney' => '5399',
            'Guinea' => '224',
            'Guinea-Bissau' => '245',
            'Guinea, Equatorial' => '240',
            'Guiana, French' => '594',
            'Guyana' => '592',
            'Haiti' => '509',
            'Holy See (Vatican City State)' => '379',
            'Holland' => '31',
            'Honduras' => '504',
            'Hong Kong' => '852',
            'Hungary' => '36',
            'Iceland' => '354',
            'India' => '91',
            'Indonesia' => '62',
            'Iran' => '98',
            'Iraq' => '964',
            'Ireland' => '353',
            'Isle of Man' => '44',
            'Israel' => '972',
            'Italy' => '39',
            'Jamaica' => '1 876',
            'Japan' => '81',
            'Jersey' => '44',
            'Jordan' => '962',
            'Kazakhstan' => '7',
            'Kenya' => '254',
            'Kiribati' => '686',
            'Korea(North)' => '850',
            'Korea(South)' => '82',
            'Kosovo' => '381',
            'Kuwait' => '965',
            'Kyrgyzstan' => '996',
            'Lao People\'s Democratic Republic' => '856',
            'Latvia' => '371',
            'Lebanon' => '961',
            'Lesotho' => '266',
            'Liberia' => '231',
            'Libyan Arab Jamahiriya' => '218',
            'Liechtenstein' => '423',
            'Lithuania' => '370',
            'Luxembourg' => '352',
            'Macao, (China)' => '853',
            'Macedonia, TFYR' => '389',
            'Madagascar' => '261',
            'Malawi' => '265',
            'Malaysia' => '60',
            'Maldives' => '960',
            'Mali' => '223',
            'Malta' => '356',
            'Marshall Islands' => '692',
            'Martinique' => '596',
            'Mauritania' => '222',
            'Mauritius' => '230',
            'Mayotte' => '262',
            'Mexico' => '52',
            'Micronesia' => '691',
            'Moldova' => '373',
            'Monaco' => '377',
            'Mongolia' => '976',
            'Montenegro' => '382',
            'Montserrat' => '1664',
            'Morocco' => '212',
            'Mozambique' => '258',
            'Myanmar' => '95',
            'Namibia' => '264',
            'Nauru' => '674',
            'Nepal' => '977',
            'Netherlands' => '31',
            'Netherlands Antilles' => '599',
            'New Caledonia' => '687',
            'New Zealand' => '64',
            'Nicaragua' => '505',
            'Niger' => '227',
            'Nigeria' => '234',
            'Niue' => '683',
            'Norfolk Island' => '672',
            'Northern Mariana Islands' => '1670',
            'Norway' => '47',
            'Oman' => '968',
            'Pakistan' => '92',
            'Palau' => '680',
            'Palestinian Territory' => '970',
            'Panama' => '507',
            'Papua New Guinea' => '675',
            'Paraguay' => '595',
            'Peru' => '51',
            'Philippines' => '63',
            'Pitcairn Island' => '872',
            'Poland' => '48',
            'Portugal' => '351',
            'Puerto Rico' => '1787',
            'Qatar' => '974',
            'Reunion' => '262',
            'Romania' => '40',
            'Russia' => '7',
            'Rwanda' => '250',
            'Sahara' => '212',
            'Saint Helena' => '290',
            'Saint Kitts and Nevis' => '1869',
            'Saint Lucia' => '1758',
            'Saint Pierre and Miquelon' => '508',
            'Saint Vincent and the Grenadines' => '1784',
            'Samoa' => '685',
            'San Marino' => '374',
            'Sao Tome and Principe' => '239',
            'Saudi Arabia' => '966',
            'Senegal' => '221',
            'Serbia' => '381',
            'Seychelles' => '248',
            'Sierra Leone' => '232',
            'Singapore' => '65',
            'Slovakia' => '421',
            'Slovenia' => '386',
            'Solomon Islands' => '677',
            'Somalia' => '252',
            'South Africa' => '27',
            'S. Georgia and S. Sandwich Is.' => '500',
            'Spain' => '34',
            'Sri Lanka (ex-Ceilan)' => '94',
            'Sudan' => '249',
            'Suriname' => '597',
            'Svalbard and Jan Mayen Islands' => '79',
            'Swaziland' => '41',
            'Sweden' => '46',
            'Switzerland' => '41',
            'Syrian Arab Republic' => '963',
            'Taiwan' => '886',
            'Tajikistan' => '992',
            'Tanzania' => '255',
            'Thailand' => '66',
            'Timor-Leste (East Timor)' => '670',
            'Togo' => '228',
            'Tokelau' => '690',
            'Tonga' => '676',
            'Trinidad and Tobago' => '1 868',
            'Tunisia' => '216',
            'Turkey' => '90',
            'Turkmenistan' => '993',
            'Turks and Caicos Islands' => '1 649',
            'Tuvalu' => '688',
            'Uganda' => '256',
            'Ukraine' => '380',
            'United Arab Emirates' => '971',
            'United Kingdom' => '44',
            'United States' => '1',
            'US Minor Outlying Islands' => '808',
            'Uruguay' => '598',
            'Uzbekistan' => '998',
            'Vanuatu' => '678',
            'Vatican City State (Holy See)' => '379',
            'Venezuela' => '58',
            'Viet Nam' => '84',
            'Virgin Islands, British' => '1284',
            'Virgin Islands, U.S.' => '1340',
            'Wallis and Futuna' => '681',
            'Western Sahara' => '212',
            'Yemen' => '967',
            'Zambia' => '260',
            'Zimbabwe' => '263',
        );
    
        return $onlyName ? array_keys($countries) : $countries;
    }
}

/**
 * 生成电话国码下拉选择表
 * 
 * @param  string  选择框名称
 * @param  string  选择框值
 * @param  bool    是否只输入 options
 * @return void
 */
if ( ! function_exists('callingCodeSelecter'))
{
    function callingCodeSelecter($name = '', $val = '', $onlyOptions = FALSE)
    {
        $output = $onlyOptions ? '' : sprintf('<select name="%s" id="%s" class="form-control phone-code">', $name, $name);

        foreach (countries() as $country => $code)
        {
            $output .= sprintf('<option data-country="%s" value="%s"%s>%s (+%s)</option>', $country, $code, ($val == $country OR $val == $code) ? ' selected' : '', $country, $code);
        }

        $output .= $onlyOptions ? '' : '</select>';

        echo $output;        
    }
}

if ( ! function_exists('streetType'))
{
    function streetType()
    {
        $types =  array(
          'ALLEY',
          'APPROACH',
          'ARCADE',
          'AVENUE',
          'BOULEVARD',
          'BROW',
          'BYPASS',
          'CAUSEWAY',
          'CIRCUIT',
          'CIRCUS',
          'CLOSE',
          'COPSE',
          'CORNER',
          'COVE',
          'COURT',
          'CRESCENT',
          'DRIVE',
          'END',
          'ESPLANANDE',
          'FLAT',
          'FREEWAY',
          'FRONTAGE',
          'GARDENS',
          'GLADE',
          'GLEN',
          'GREEN',
          'GROVE',
          'HEIGHTS',
          'HIGHWAY',
          'LANE',
          'LINK',
          'LOOP',
          'MALL',
          'MEWS',
          'PACKET',
          'PARADE',
          'PARK',
          'PARKWAY',
          'PLACE',
          'PROMENADE',
          'RESERVE',
          'RIDGE',
          'RISE',
          'ROAD',
          'ROW',
          'SQUARE',
          'STREET',
          'STRIP',
          'TARN',
          'TERRACE',
          'THOROUGHFARE',
          'TRACK',
          'TRUNKWAY',
          'VIEW',
          'VISTA',
          'WALK',
          'WAY',
          'WALKWAY',
          'YARD'                  
        );
    
        return array_combine($types, $types);
    }
}


 
