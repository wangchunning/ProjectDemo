<?php
/**
 *  货币相关辅助函数
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */

// ------------------------------------------------------------------------


/**
 * 获取所有货币的名称和符号
 *
 *
 * @return array
 */
if ( ! function_exists('currencySymbols'))
{
    function currencySymbols()
    {
        $symbols = array(  
            'aed' => array('name' => 'UAE Dirham', 'country' => 'ae'),   
            'afn' => array('name' => 'Afghani', 'symbol' => '؋', 'country' => 'af'),   
            'all' => array('name' => 'Lek', 'symbol' => 'Lek', 'country' => 'al'),   
            'amd' => array('name' => 'Armenian Dram', 'symbol' => 'Դ', 'country' => 'am'),   
            'ang' => array('name' => 'Netherlands Antillian Guilder', 'symbol' => 'ƒ', 'country' => 'an'),   
            'aoa' => array('name' => 'Kwanza', 'symbol' => 'Kz', 'country' => 'ao'),   
            'ars' => array('name' => 'Argentine Peso', 'symbol' => '$', 'country' => 'ar'),   
            'aud' => array('name' => 'Australian Dollar', 'symbol' => '$', 'country' => 'au'),   
            'awg' => array('name' => 'Aruban Guilder', 'symbol' => 'ƒ', 'country' => 'aw'),   
            'azn' => array('name' => 'Azerbaijanian Manat', 'symbol' => 'ман', 'country' => 'az'),   
            'bam' => array('name' => 'Convertible Marks', 'symbol' => 'KM', 'country' => 'ba'),   
            'bbd' => array('name' => 'Barbados Dollar', 'symbol' => '$', 'country' => 'bb'),   
            'bdt' => array('name' => 'Taka', 'country' => 'Bangladesh', 'country' => 'bd'),   
            'bgn' => array('name' => 'Bulgarian Lev', 'symbol' => 'лв', 'country' => 'bg'),   
            'bhd' => array('name' => 'Bahraini Dinar', 'country' => 'Bahrain', 'country' => 'bh'),   
            'bif' => array('name' => 'Burundi Franc', 'symbol' => 'FBu', 'country' => 'bi'),   
            'bmd' => array('name' => 'Bermudian Dollar', 'symbol' => '$', 'country' => 'bm'),   
            'bnd' => array('name' => 'Brunei Dollar', 'symbol' => '$', 'country' => 'bn'),   
            'bob' => array('name' => 'Boliviano', 'symbol' => '$b', 'country' => 'bo'),   
            'bov' => array('name' => 'Mvdol', 'country' => 'bo'),   
            'brl' => array('name' => 'Brazilian Real', 'symbol' => 'R$', 'country' => 'br'),   
            'bsd' => array('name' => 'Bahamian Dollar', 'symbol' => '$', 'country' => 'bs'),   
            'btn' => array('name' => 'Ngultrum', 'symbol' => 'Nu.', 'country' => 'bt'),   
            'bwp' => array('name' => 'Pula', 'symbol' => 'P', 'country' => 'bw'),   
            'byr' => array('name' => 'Belarussian Ruble', 'symbol' => 'p.', 'country' => 'by'),   
            'bzd' => array('name' => 'Belize Dollar', 'symbol' => 'BZ$', 'country' => 'bz'),   
            'cad' => array('name' => 'Canadian Dollar', 'symbol' => '$', 'country' => 'ca'),   
            'cdf' => array('name' => 'Congolese Franc', 'symbol' => 'FC', 'country' => 'cd'),   
            'che' => array('name' => 'WIR Euro', 'country' => 'ch'),   
            'chf' => array('name' => 'Swiss Franc', 'country' => 'ch'),   
            'chw' => array('name' => 'WIR Franc', 'country' => 'ch'),   
            'clf' => array('name' => 'Unidades de fomento', 'symbol' => 'UF', 'country' => 'cl'),   
            'clp' => array('name' => 'Chilean Peso', 'symbol' => '$', 'country' => 'cl'),   
            'cny' => array('name' => 'Yuan Renminbi', 'symbol' => '¥', 'country' => 'cn'),   
            'cop' => array('name' => 'Colombian Peso', 'symbol' => '$', 'country' => 'co'),   
            'cou' => array('name' => 'Unidad de Valor Real', 'country' => 'co'),   
            'crc' => array('name' => 'Costa Rican Colon', 'symbol' => '₡', 'country' => 'cr'),   
            'cuc' => array('name' => 'Peso Convertible', 'symbol' => '$', 'country' => 'cu'),   
            'cup' => array('name' => 'Cuban Peso', 'symbol' => '₱', 'country' => 'cu'),   
            'cve' => array('name' => 'Cape Verde Escudo', 'symbol' => '$', 'country' => 'cv'),   
            'czk' => array('name' => 'Czech Koruna', 'symbol' => 'Kč', 'country' => 'cz'),   
            'djf' => array('name' => 'Djibouti Franc', 'symbol' => 'Fdj', 'country' => 'dj'),   
            'dkk' => array('name' => 'Danish Krone', 'symbol' => 'kr', 'country' => 'dk'),   
            'dop' => array('name' => 'Dominican Peso', 'symbol' => 'RD$', 'country' => 'do'),   
            'dzd' => array('name' => 'Algerian Dinar', 'symbol' => 'دج', 'country' => 'dz'),   
            'eek' => array('name' => 'Kroon', 'country' => 'ee'),   
            'egp' => array('name' => 'Egyptian Pound', 'symbol' => '£', 'country' => 'eg'),   
            'ern' => array('name' => 'Nakfa', 'symbol' => 'Nfk', 'country' => 'er'),   
            'etb' => array('name' => 'Ethiopian Birr', 'symbol' => 'Br', 'country' => 'et'),   
            'eur' => array('name' => 'Euro', 'symbol' => '€', 'country' => 'eu'),   
            'fjd' => array('name' => 'Fiji Dollar', 'symbol' => '$', 'country' => 'fj'),   
            'fkp' => array('name' => 'Falkland Islands Pound', 'symbol' => '£', 'country' => 'fk'),   
            'gbp' => array('name' => 'Pound Sterling', 'symbol' => '£', 'country' => 'gb'),   
            'gel' => array('name' => 'Lari', 'country' => 'ge'), 
            'ggp' => array('symbol' => '£', 'country' => 'gg'),   
            'ghc' => array('symbol' => '¢'),   
            'ghs' => array('name' => 'Cedi', 'symbol' => 'GH₵', 'country' => 'gh'),   
            'gip' => array('name' => 'Gibraltar Pound', 'symbol' => '£', 'country' => 'gi'),   
            'gmd' => array('name' => 'Dalasi', 'symbol' => 'D', 'country' => 'gm'),   
            'gnf' => array('name' => 'Guinea Franc', 'symbol' => 'FG', 'country' => 'gn'),   
            'gtq' => array('name' => 'Quetzal', 'symbol' => 'Q', 'country' => 'gt'),   
            'gyd' => array('name' => 'Guyana Dollar', 'symbol' => '$', 'country' => 'gy'),   
            'hkd' => array('name' => 'Hong Kong Dollar', 'symbol' => '$', 'country' => 'hk'),   
            'hnl' => array('name' => 'Lempira', 'symbol' => 'L', 'country' => 'hn'),   
            'hrk' => array('name' => 'Croatian Kuna', 'symbol' => 'kn', 'country' => 'hr'),   
            'htg' => array('name' => 'Gourde', 'symbol' => 'G', 'country' => 'ht'),   
            'huf' => array('name' => 'Forint', 'symbol' => 'Ft', 'country' => 'hu'),   
            'idr' => array('name' => 'Rupiah', 'symbol' => 'Rp', 'country' => 'id'),   
            'ils' => array('name' => 'New Israeli Sheqel', 'symbol' => '₪', 'country' => 'il'),   
            'imp' => array('symbol' => '£'),   
            'inr' => array('name' => 'Indian Rupee', 'country' => 'in','symbol' => '&#8377;'),   
            'iqd' => array('name' => 'Iraqi Dinar', 'symbol' => 'ع.د', 'country' => 'iq'),   
            'irr' => array('name' => 'Iranian Rial', 'symbol' => '﷼', 'country' => 'ir'),   
            'isk' => array('name' => 'Iceland Krona', 'symbol' => 'kr', 'country' => 'is'),   
            'jep' => array('symbol' => '£'),   
            'jmd' => array('name' => 'Jamaican Dollar', 'symbol' => 'J$', 'country' => 'jm'),   
            'jod' => array('name' => 'Jordanian Dinar', 'country' => 'jo'),   
            'jpy' => array('name' => 'Japanese Yen', 'symbol' => '¥', 'country' => 'jp'),   
            'kes' => array('name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'country' => 'ke'),   
            'kgs' => array('name' => 'Som', 'symbol' => 'лв', 'country' => 'kg'),   
            'khr' => array('name' => 'Riel', 'symbol' => '៛', 'country' => 'kh'),   
            'kmf' => array('name' => 'Comoro Franc', 'symbol' => 'CF', 'country' => 'km'),   
            'kpw' => array('name' => 'North Korean Won', 'symbol' => '₩', 'country' => 'kp'),   
            'krw' => array('name' => 'Won', 'symbol' => '₩', 'country' => 'kr'),   
            'kwd' => array('name' => 'Kuwaiti Dinar', 'symbol' => 'K.D.', 'country' => 'kw'),   
            'kyd' => array('name' => 'Cayman Islands Dollar', 'symbol' => '$', 'country' => 'ky'),   
            'kzt' => array('name' => 'Tenge', 'symbol' => 'лв', 'country' => 'kz'),   
            'lak' => array('name' => 'Kip', 'symbol' => '₭', 'country' => 'la'),   
            'lbp' => array('name' => 'Lebanese Pound', 'symbol' => '£', 'country' => 'lb'),   
            'lkr' => array('name' => 'Sri Lanka Rupee', 'symbol' => '₨', 'country' => 'lk'),   
            'lrd' => array('name' => 'Liberian Dollar', 'symbol' => '$', 'country' => 'lr'),   
            'lsl' => array('name' => 'Loti', 'symbol' => 'L', 'country' => 'ls'),   
            'ltl' => array('name' => 'Lithuanian Litas', 'symbol' => 'Lt', 'country' => 'lt'),   
            'lvl' => array('name' => 'Latvian Lats', 'symbol' => 'Ls', 'country' => 'lv'),   
            'lyd' => array('name' => 'Libyan Dinar', 'symbol' => 'LD', 'country' => 'ly'),   
            'mad' => array('name' => 'Moroccan Dirham', 'symbol' => 'م.', 'country' => 'ma'),   
            'mdl' => array('name' => 'Moldovan Leu', 'country' => 'md'),   
            'mga' => array('name' => 'Malagasy Ariary', 'symbol' => 'Ar', 'country' => 'mg'),   
            'mkd' => array('name' => 'Denar', 'symbol' => 'ден', 'country' => 'mk'),   
            'mmk' => array('name' => 'Kyat', 'symbol' => 'K', 'country' => 'mm'),   
            'mnt' => array('name' => 'Tugrik', 'symbol' => '₮', 'country' => 'mn'),   
            'mop' => array('name' => 'Pataca', 'symbol' => 'MOP$', 'country' => 'mo'),   
            'mro' => array('name' => 'Ouguiya', 'symbol' => 'UM', 'country' => 'mr'),   
            'mur' => array('name' => 'Mauritius Rupee', 'symbol' => '₨', 'country' => 'mu'),   
            'mvr' => array('name' => 'Rufiyaa', 'symbol' => 'Rf', 'country' => 'mv'),   
            'mwk' => array('name' => 'Kwacha', 'symbol' => 'MK', 'country' => 'mw'),   
            'mxn' => array('name' => 'Mexican Peso', 'symbol' => '$', 'country' => 'mx'),   
            'mxv' => array('name' => 'Mexican Unidad de Inversion (UDI)', 'country' => 'mx'),   
            'myr' => array('name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'country' => 'my'),   
            'mzn' => array('name' => 'Metical', 'symbol' => 'MT', 'country' => 'mz'),   
            'nad' => array('name' => 'Namibia Dollar', 'symbol' => '$', 'country' => 'na'),   
            'ngn' => array('name' => 'Naira', 'symbol' => '₦', 'country' => 'ng'),   
            'nio' => array('name' => 'Cordoba Oro', 'symbol' => 'C$', 'country' => 'ni'),   
            'nok' => array('name' => 'Norwegian Krone', 'symbol' => 'kr', 'country' => 'no'),   
            'npr' => array('name' => 'Nepalese Rupee', 'symbol' => '₨', 'country' => 'np'),   
            'nzd' => array('name' => 'New Zealand Dollar', 'symbol' => '$', 'country' => 'nz'),   
            'omr' => array('name' => 'Rial Omani', 'symbol' => '﷼', 'country' => 'om'),   
            'pab' => array('name' => 'Balboa', 'symbol' => 'B/.', 'country' => 'pa'),   
            'pen' => array('name' => 'Nuevo Sol', 'symbol' => 'S/.', 'country' => 'pe'),   
            'pgk' => array('name' => 'Kina', 'symbol' => 'K', 'country' => 'pg'),   
            'php' => array('name' => 'Philippine Peso', 'symbol' => 'Php', 'country' => 'ph'),   
            'pkr' => array('name' => 'Pakistan Rupee', 'symbol' => '₨', 'country' => 'pk'),   
            'pln' => array('name' => 'Zloty', 'symbol' => 'zł', 'country' => 'pl'),   
            'pyg' => array('name' => 'Guarani', 'symbol' => 'Gs', 'country' => 'py'),   
            'qar' => array('name' => 'Qatari Rial', 'symbol' => '﷼', 'country' => 'qa'),   
            'ron' => array('name' => 'New Leu', 'symbol' => 'lei', 'country' => 'ro'),   
            'rsd' => array('name' => 'Serbian Dinar', 'symbol' => 'Дин.', 'country' => 'rs'),   
            'rub' => array('name' => 'Russian Ruble', 'symbol' => 'руб', 'country' => 'ru'),   
            'rwf' => array('name' => 'Rwanda Franc', 'symbol' => 'FRw', 'country' => 'rw'),   
            'sar' => array('name' => 'Saudi Riyal', 'symbol' => '﷼', 'country' => 'sa'),   
            'sbd' => array('name' => 'Solomon Islands Dollar', 'symbol' => '$', 'country' => 'sb'),   
            'scr' => array('name' => 'Seychelles Rupee', 'symbol' => '₨', 'country' => 'sc'),   
            'sdg' => array('name' => 'Sudanese Pound', 'country' => 'sd'),   
            'sek' => array('name' => 'Swedish Krona', 'symbol' => 'kr', 'country' => 'se'),   
            'sgd' => array('name' => 'Singapore Dollar', 'symbol' => '$', 'country' => 'sg'),   
            'shp' => array('name' => 'Saint Helena Pound', 'symbol' => '£', 'country' => 'sh'),   
            'sll' => array('name' => 'Leone', 'symbol' => 'Le', 'country' => 'sl'),   
            'sos' => array('name' => 'Somali Shilling', 'symbol' => 'S', 'country' => 'so'),   
            'srd' => array('name' => 'Surinam Dollar', 'symbol' => '$', 'country' => 'sr'),   
            'std' => array('name' => 'Dobra', 'symbol' => 'Db', 'country' => 'st'),   
            'svc' => array('name' => 'El Salvador Colon', 'symbol' => '$', 'country' => 'sv'),   
            'syp' => array('name' => 'Syrian Pound', 'symbol' => '£', 'country' => 'sy'),   
            'szl' => array('name' => 'Lilangeni', 'symbol' => 'L', 'country' => 'sz'),   
            'thb' => array('name' => 'Baht', 'symbol' => '฿', 'country' => 'th'),   
            'tjs' => array('name' => 'Somoni', 'country' => 'tj'),   
            'tmt' => array('name' => 'Manat', 'symbol' => 'm', 'country' => 'tm'),   
            'tnd' => array('name' => 'Tunisian Dinar', 'symbol' => 'DT', 'country' => 'tn'),   
            'top' => array('name' => 'Pa\'anga', 'symbol' => 'DT', 'country' => 'to'),   
            'try' => array('name' => 'Turkish Lira', 'symbol' => 'TL', 'country' => 'tr'),   
            'ttd' => array('name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$', 'country' => 'tt'),   
            'tvd' => array('symbol' => '$'),   
            'twd' => array('name' => 'New Taiwan Dollar', 'symbol' => 'NT$', 'country' => 'tw'),   
            'tzs' => array('name' => 'Tanzanian Shilling', 'country' => 'tz'),   
            'uah' => array('name' => 'Hryvnia', 'symbol' => '₴', 'country' => 'ua'),   
            'ugx' => array('name' => 'Uganda Shilling', 'symbol' => 'USh', 'country' => 'ug'),   
            'usd' => array('name' => 'US Dollar', 'symbol' => '$', 'country' => 'us'),   
            'usn' => array('name' => 'US Dollar (Next day)', 'country' => 'us'),   
            'uss' => array('name' => 'US Dollar (Same day)', 'country' => 'us'),   
            'uyi' => array('name' => 'Uruguay Peso en Unidades Indexadas', 'country' => 'uy'),   
            'uyu' => array('name' => 'Peso Uruguayo', 'symbol' => '$U', 'country' => 'uy'),   
            'uzs' => array('name' => 'Uzbekistan Sum', 'symbol' => 'лв', 'country' => 'uz'),   
            'vef' => array('name' => 'Bolivar Fuerte', 'symbol' => 'Bs', 'country' => 've'),   
            'vnd' => array('name' => 'Dong', 'symbol' => '₫', 'country' => 'vn'),   
            'vuv' => array('name' => 'Vatu', 'symbol' => 'VT', 'country' => 'vu'),   
            'wst' => array('name' => 'Tala', 'country' => 'ws'),   
            'xaf' => array('name' => 'CFA Franc BEAC', 'symbol' => 'FCFA', 'country' => 'cm'),   
            'xag' => array('name' => 'Silver'), 
            'xau' => array('name' => 'Gold'),   
            'xba' => array('name' => 'Bond Markets Units European Composite Unit (EURCO)'),   
            'xbb' => array('name' => 'European Monetary Unit (E.M.U.-6)'),   
            'xbc' => array('name' => 'European Unit of Account 9(E.U.A.-9)'),   
            'xbd' => array('name' => 'European Unit of Account 17(E.U.A.-17)'),   
            'xcd' => array('name' => 'East Caribbean Dollar', 'symbol' => '$', 'country' => 'kn'), 
            'xdr' => array('name' => 'SDR'),   
            'xfu' => array('name' => 'UIC-Franc'),   
            'xof' => array('name' => 'CFA Franc BCEAO', 'symbol' => 'CFA', 'country' => 'sn'),   
            'xpd' => array('name' => 'Palladium'),   
            'xpf' => array('name' => 'CFP Franc', 'country' => 'pf'),   
            'xpt' => array('name' => 'Platinum'),   
            'xts' => array('name' => 'Codes specifically reserved for testing purposes'),   
            'xxx' => array('name' => 'The codes assigned for transactions where no currency is involved are:'),   
            'yer' => array('name' => 'Yemeni Rial', 'symbol' => '﷼', 'country' => 'ye'),   
            'zar' => array('name' => 'Rand', 'symbol' => 'R', 'country' => 'za'),   
            'zmk' => array('name' => 'Zambian Kwacha', 'symbol' => 'ZK', 'country' => 'zm'),   
            'zwd' => array('symbol' => 'Z$'),   
            'zwl' => array('name' => 'Zimbabwe Dollar', 'symbol' => '$', 'country' => 'zw'),  
        );  

        return $symbols;
    }
}

/**
 * 返回货币的名称
 * 
 * @param  string  货币符号
 * @return string
 */
if ( ! function_exists('currencyName'))
{
    function currencyName($currency)
    {   
        $names = currencySymbols();

        $currency = strtolower($currency);

        if ( ! isset($names[$currency]))
        {
            return $currency;
        }

        return isset($names[$currency]['name']) ? $names[$currency]['name'] : $currency;
    }
}

/**
 * 返回货币对应的国家
 * 
 * @param  string  货币符号
 * @return string
 */
if ( ! function_exists('currencyCountry'))
{
    function currencyCountry($currency)
    {   
        $names = currencySymbols();

        $currency = strtolower($currency);

        if ( ! isset($names[$currency]) OR ! isset($names[$currency]['country']))
        {
            return $currency;
        }
        
        $ab = $names[$currency]['country'];
        return countryName($ab);       
    }
}

/**
 * 获取国家的名称
 *
 *
 * @param  国家缩写
 * @return array
 */
if ( ! function_exists('countryName'))
{
    function countryName($ab = '')
    {        
        $countries=array(
            'ad'=>'Andorra',
            'ae'=>'United Arab Emirates',
            'af'=>'Afghanistan',
            'ag'=>'Antigua and Barbuda',
            'ai'=>'Anguilla',
            'al'=>'Albania',
            'am'=>'Armenia',
            'an'=>'Netherlands Antilles',
            'ao'=>'Angola',
            'aq'=>'Antarctica',
            'ar'=>'Argentina',
            'as'=>'American Samoa',
            'at'=>'Austria',
            'au'=>'Australia',
            'aw'=>'Aruba',
            'ax'=>'Åland Islands',
            'az'=>'Azerbaijan',
            'ba'=>'Bosnia and Herzegovina',
            'bb'=>'Barbados',
            'bd'=>'Bangladesh',
            'be'=>'Belgium',
            'bf'=>'Burkina Faso',
            'bg'=>'Bulgaria',
            'bh'=>'Bahrain',
            'bi'=>'Burundi',
            'bj'=>'Benin',
            'bl'=>'Saint Barthélemy',
            'bm'=>'Bermuda',
            'bn'=>'Brunei', #'Brunei Darussalam',
            'bo'=>'Bolivia', #'Bolivia, Plurinational State of',
            'bq'=>'Caribbean Netherlands', #'Bonaire, Sint Eustatius and Saba',
            'br'=>'Brazil',
            'bs'=>'Bahamas',
            'bt'=>'Bhutan',
            'bv'=>'Bouvet Island',
            'bw'=>'Botswana',
            'by'=>'Belarus',
            'bz'=>'Belize',
            'ca'=>'Canada',
            'cc'=>'Cocos (Keeling) Islands',
            'cd'=>'DR Congo', #'Congo, the Democratic Republic of the',
            'cf'=>'Central African Republic',
            'cg'=>'Congo',
            'ch'=>'Switzerland',
            'ci'=>'Côte d\'Ivoire',
            'ck'=>'Cook Islands',
            'cl'=>'Chile',
            'cm'=>'Cameroon',
            'cn'=>'China',
            'co'=>'Colombia',
            'cr'=>'Costa Rica',
            'cu'=>'Cuba',
            'cv'=>'Cape Verde',
            'cw'=>'Curaçao',
            'cx'=>'Christmas Island',
            'cy'=>'Cyprus',
            'cz'=>'Czech Republic',
            'de'=>'Germany',
            'dj'=>'Djibouti',
            'dk'=>'Denmark',
            'dm'=>'Dominica',
            'do'=>'Dominican Republic',
            'dz'=>'Algeria',
            'ec'=>'Ecuador',
            'ee'=>'Estonia',
            'eg'=>'Egypt',
            'eh'=>'Western Sahara',
            'en'=>'England',
            'er'=>'Eritrea',
            'es'=>'Spain',
            'et'=>'Ethiopia',
            'eu'=>'European Union',
            'fi'=>'Finland',
            'fj'=>'Fiji',
            'fk'=>'Falkland Islands', #'Falkland Islands (Malvinas)',
            'fm'=>'Micronesia', #'Micronesia, Federated States of',
            'fo'=>'Faroe Islands',
            'fr'=>'France',
            'ga'=>'Gabon',
            'gb'=>'United Kingdom',
            'gd'=>'Grenada',
            'ge'=>'Georgia',
            'gf'=>'French Guiana',
            'gg'=>'Guernsey',
            'gh'=>'Ghana',
            'gi'=>'Gibraltar',
            'gl'=>'Greenland',
            'gm'=>'Gambia',
            'gn'=>'Guinea',
            'gp'=>'Guadeloupe',
            'gq'=>'Equatorial Guinea',
            'gr'=>'Greece',
            'gs'=>'South Georgia and the South Sandwich Islands',
            'gt'=>'Guatemala',
            'gu'=>'Guam',
            'gw'=>'Guinea-Bissau',
            'gy'=>'Guyana',
            'hk'=>'Hong Kong',
            'hm'=>'Heard Island and McDonald Islands',
            'hn'=>'Honduras',
            'hr'=>'Croatia',
            'ht'=>'Haiti',
            'hu'=>'Hungary',
            'id'=>'Indonesia',
            'ie'=>'Ireland',
            'il'=>'Israel',
            'im'=>'Isle of Man',
            'in'=>'India',
            'io'=>'British Indian Ocean Territory',
            'iq'=>'Iraq',
            'ir'=>'Iran', #'Iran, Islamic Republic of',
            'is'=>'Iceland',
            'it'=>'Italy',
            'je'=>'Jersey',
            'jm'=>'Jamaica',
            'jo'=>'Jordan',
            'jp'=>'Japan',
            'ke'=>'Kenya',
            'kg'=>'Kyrgyzstan',
            'kh'=>'Cambodia',
            'ki'=>'Kiribati',
            'km'=>'Comoros',
            'kn'=>'Saint Kitts and Nevis',
            'kp'=>'Korea DPR', #Korea, Democratic People's Republic of
            'kr'=>'Korea Republic', #Korea, Republic of
            'kw'=>'Kuwait',
            'ky'=>'Cayman Islands',
            'kz'=>'Kazakhstan',
            'la'=>'Laos', #'Lao People\'s Democratic Republic',
            'lb'=>'Lebanon',
            'lc'=>'Saint Lucia',
            'li'=>'Liechtenstein',
            'lk'=>'Sri Lanka',
            'lr'=>'Liberia',
            'ls'=>'Lesotho',
            'lt'=>'Lithuania',
            'lu'=>'Luxembourg',
            'lv'=>'Latvia',
            'ly'=>'Libya', #'Libyan Arab Jamahiriya',
            'ma'=>'Morocco',
            'mc'=>'Monaco',
            'md'=>'Moldova', #'Moldova, Republic of',
            'me'=>'Montenegro',
            'mf'=>'Saint Martin', #'Saint Martin (French part)',
            'mg'=>'Madagascar',
            'mh'=>'Marshall Islands',
            'mk'=>'Macedonia', #'Macedonia, the former Yugoslav Republic of',
            'ml'=>'Mali',
            'mm'=>'Myanmar', #'Myanmar (Burma)',
            'mn'=>'Mongolia',
            'mo'=>'Macao',
            'mp'=>'Northern Mariana Islands',
            'mq'=>'Martinique',
            'mr'=>'Mauritania',
            'ms'=>'Montserrat',
            'mt'=>'Malta',
            'mu'=>'Mauritius',
            'mv'=>'Maldives',
            'mw'=>'Malawi',
            'mx'=>'Mexico',
            'my'=>'Malaysia',
            'mz'=>'Mozambique',
            'na'=>'Namibia',
            'nc'=>'New Caledonia',
            'ne'=>'Niger',
            'nf'=>'Norfolk Island',
            'ng'=>'Nigeria',
            'ni'=>'Nicaragua',
            'nl'=>'Netherlands',
            'no'=>'Norway',
            'np'=>'Nepal',
            'nr'=>'Nauru',
            'nu'=>'Niue',
            'nz'=>'New Zealand',
            'om'=>'Oman',
            'pa'=>'Panama',
            'pe'=>'Peru',
            'pf'=>'French Polynesia',
            'pg'=>'Papua New Guinea',
            'ph'=>'Philippines',
            'pk'=>'Pakistan',
            'pl'=>'Poland',
            'pm'=>'Saint Pierre and Miquelon',
            'pn'=>'Pitcairn',
            'pr'=>'Puerto Rico',
            'ps'=>'Palestine', #'Palestinian Territory, Occupied',
            'pt'=>'Portugal',
            'pw'=>'Palau',
            'py'=>'Paraguay',
            'qa'=>'Qatar',
            're'=>'Réunion',
            'ro'=>'Romania',
            'rs'=>'Serbia',
            'ru'=>'Russia', #'Russian Federation',
            'rw'=>'Rwanda',
            'sa'=>'Saudi Arabia',
            'sb'=>'Solomon Islands',
            'sc'=>'Seychelles',
            'sd'=>'Sudan',
            'se'=>'Sweden',
            'sg'=>'Singapore',
            'sh'=>'Saint Helena, Ascension and Tristan da Cunha',
            'si'=>'Slovenia',
            'sj'=>'Svalbard and Jan Mayen',
            'sk'=>'Slovakia',
            'sl'=>'Sierra Leone',
            'sm'=>'San Marino',
            'sn'=>'Senegal',
            'so'=>'Somalia',
            'sr'=>'Suriname',
            'ss'=>'South Sudan',
            'st'=>'Sao Tome and Principe',
            'sv'=>'El Salvador',
            'sx'=>'Sint Maarten',
            'sy'=>'Syria', #'Syrian Arab Republic',
            'sz'=>'Swaziland',
            'tc'=>'Turks and Caicos Islands',
            'td'=>'Chad',
            'tf'=>'French Southern Territories',
            'tg'=>'Togo',
            'th'=>'Thailand',
            'tj'=>'Tajikistan',
            'tk'=>'Tokelau',
            'tl'=>'Timor-Leste',
            'tm'=>'Turkmenistan',
            'tn'=>'Tunisia',
            'to'=>'Tonga',
            'tr'=>'Turkey',
            'tt'=>'Trinidad and Tobago',
            'tv'=>'Tuvalu',
            'tw'=>'Taiwan', #'Taiwan, Province of China',
            'tz'=>'Tanzania', #'Tanzania, United Republic of',
            'ua'=>'Ukraine',
            'ug'=>'Uganda',
            'um'=>'U.S. Minor Outlying Islands', #'United States Minor Outlying Islands',
            'us'=>'United States',
            'uy'=>'Uruguay',
            'uz'=>'Uzbekistan',
            'va'=>'Vatican City', #'Holy See (Vatican City State)',
            'vc'=>'Saint Vincent and the Grenadines',
            've'=>'Venezuela', #'Venezuela, Bolivarian Republic of',
            'vg'=>'British Virgin Islands', #'Virgin Islands, British',
            'vi'=>'U.S. Virgin Islands', #'Virgin Islands, U.S.',
            'vn'=>'Vietnam', #'Viet Nam',
            'vu'=>'Vanuatu',
            'wf'=>'Wallis and Futuna',
            'ws'=>'Samoa',
            'ye'=>'Yemen',
            'yt'=>'Mayotte',
            'za'=>'South Africa',
            'zm'=>'Zambia',
            'zw'=>'Zimbabwe'
        );

        if ( ! $ab)
        {
            return $countries;
        }
    
        $ab = strtolower($ab);

        if ( ! isset($countries[$ab]))
        {
            return $ab;
        }

        return $countries[$ab];
    }
}

/**
 * 格式化货币，输出带货币符号的金额显示，如 $10,000.00
 *
 * @param  string  货币简写
 * @param  float   金额
 * @param  string  尾末是否显示货币简写
 * @param  string  显示正负号
 * @return string
 */
if ( ! function_exists('currencyFormat'))
{
    function currencyFormat($amount, $sign = '', $show_sign = FALSE)
    {
        if (!$show_sign)
        {
        	return sprintf('￥%s', number_format($amount, 2));
        }

        $_style = "color:red";
        if ($sign == '+')
        {
        	$_style = "color:green";
        }
        
        return sprintf('<span style="%s">%s￥%s</span>', $_style, $sign, number_format(abs($amount), 2));
    }
}

/**
 * 生成所有货币的下拉选择框
 * 
 * @param  string  选择框名称
 * @param  string  选择框值
 * @param  bool    是否只输入 options
 * @return void
 */
if ( ! function_exists('allCurrenciesSelecter'))
{
    function allCurrenciesSelecter($name = '', $val = '', $onlyOptions = FALSE)
    {
        $currencies = array('AUD', 'CAD', 'CNY', 'EUR', 'GBP', 'HKD', 'JPY', 'NZD', 'SGD', 'USD');

        echo generateCurrencySelecter($currencies, $name, $val, $onlyOptions);
    }
}

/**
 * 生成货币下拉选择框
 * 
 * @param  string  选择框名称
 * @param  string  选择框值
 * @param  bool    是否只输入 options
 * @return string
 */
if ( ! function_exists('currencySelecter'))
{
    function currencySelecter($name = '', $val = '', $onlyOptions = FALSE)
    {   
        // 银行支持的货币
        $bank = new Bank;
        $currencies = $bank->getFundCurrencies();

        // 已登录？加入用户有余额的货币
        if (is_login())
        {
            $user = real_user();
            foreach ($user->balances()->get() as $balance)
            {
                if ($balance->available_amount <= 0 )
                {
                    continue;
                }

                $currencies[] = $balance->currency;
            }
        }

        return generateCurrencySelecter(array_unique($currencies), $name, $val, $onlyOptions);     
    }
}

/**
 * Add Fund 只支持的货币下拉框
 * 
 * @param  string  下拉框的名称
 * @param  string  下拉框的默认值 
 * @param  bool    是否只生成 options
 * @return string
 */
if ( ! function_exists('fundSupportSelecter'))
{
    function fundSupportSelecter($name = '', $val = '', $onlyOptions = FALSE)
    {   
        if ( ! $name)
        {
            $name = 'currency';
        }

        $bank = new Bank;
        $currencies = $bank->getFundCurrencies();

        return generateCurrencySelecter($currencies, $name, $val, $onlyOptions);
    }
}

/**
 * 货币对应旗帜
 *
 *
 * @param  string  货币标识
 * @return string
 */
if ( ! function_exists('currencyFlag'))
{
    function currencyFlag($currency = 'AUD')
    {
        return sprintf('<img class="currency" src="%s.png" alt="%s" title="%s"> ', URL::asset('assets/img/flags/' . $currency), $currency, $currency);
    }
}

/**
 * 获得货币换汇的字符串，结果以逗号隔开，如“AUDCNY,AUDUSD,AUDEUR”
 *
 * @return string
 */ 
if ( ! function_exists('currencyString'))
{
    function currencyString()
    {
        $output = array();

        $rates = new Rate;

        foreach ($rates::all() as $rate)
        {
            $output[] = $rate->from . $rate->to;
        }

        return implode($output, ',');
    }
}

/**
 *  生成下拉选择框
 *
 * @param  array   下拉框内容数组
 * @param  string  下拉框的名称
 * @param  string  下拉框的默认值 
 * @param  bool    是否只生成 options
 * @return string
 */
if ( ! function_exists('generateCurrencySelecter'))
{
    function generateCurrencySelecter($currencies, $name = '', $val = '', $onlyOptions = FALSE)
    {
        $output = $onlyOptions ? '' : sprintf('<select name="%s" id="%s" class="form-control">', $name, $name);

        foreach ($currencies as $currency)
        {
            $output .= sprintf('<option data-iconurl="%s.png" value="%s"%s>%s - %s</option>', URL::asset('assets/img/flags/' . $currency), $currency, $val == $currency ? ' selected' : '', $currency, currencyName($currency));
        }

        $output .= $onlyOptions ? '' : '</select>';

        return $output;
    }
}

if ( ! function_exists('allCurrencies'))
{
    function allCurrencies()
    {
        $_ret_array = array();
        /**
         * 可以用 Eloquent 实现？
         */
        $_currencies_to = DB::table('rates')->select(DB::raw('distinct `to`'));
        $_currencies = DB::table('rates')->select(DB::raw('distinct `from`'))
                                ->union($_currencies_to )
                                ->get();
                                

        foreach ($_currencies as $_s_currency)
        {
            $_ret_array[] = $_s_currency->from;
        }

        return $_ret_array;
    }
}
