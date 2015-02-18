<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//


function real_load_data($source_data, $db_name,
                $table_name, $columns, $on_dup_columns, $mysql)
{
     $_column_cnt = count($columns);
     $_dup_col_cnt = count($on_dup_columns);
     
     $_sql = "INSERT INTO " . "`".$db_name."`.`".$table_name;
     $_sql .= "`(`" .implode("`, `", $columns)."`)". "VALUES";
     $_ret = 0;


     for ($__i = 0; $__i < count($source_data); $__i++)
     {
           $__i == 0 ? $_sql .= "(" : $_sql .= ",(";
           for ($__j = 0; $__j < $_column_cnt; $__j++)
           {
                $col_value = $source_data[$__i][$columns[$__j]];
                $_real_val = mysql_real_escape_string($col_value, $mysql);


                $__j == 0 ? $_sql .= "'".$_real_val."'" :
                     $_sql .= ", '".$_real_val."'";
           }
           $_sql .= ")";
     }


     $_sql .= "ON DUPLICATE KEY UPDATE ";
     for ($__i = 0; $__i < $_dup_col_cnt; $__i++)
     {
           $_col_name = $on_dup_columns[$__i];
           $__i == 0 ? $_sql .= "`".$_col_name."` = VALUES(`".$_col_name."`)" :
                $_sql .= ", `".$_col_name."` = VALUES(`".$_col_name."`)";
     }

     if (!mysql_query($_sql, $mysql))
    {

     return false;
    }


     //write_log('INFO', '[import succ cnt]'.count($source_data).'[sql]'.$_sql);


     return true;
}

date_default_timezone_set('Asia/Shanghai');

$_mysql = mysql_connect("localhost:3306", "root", "root");
if (!$_mysql)
{
	echo mysql_error();
   return false;
}

if (!mysql_query("set names utf8"))
{
	return false;
}

$today = date('Y/m/d');
$date_time = date('Y-m-d H:i:s');
$data = array(
    'cpzt' => '02',
    'mjjsrq' => $today,
    'pagenum' => '1',
);
$para = http_build_query($data, '', '&');
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://www.chinawealth.com.cn/lccpAllProJzyServlet.go");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $para);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$_resp = curl_exec ($ch);

curl_close ($ch);

// further processing ....


$_resp = json_decode($_resp, true);
$_count = $_resp['Count'];
$_lists = $_resp['List'];
if (empty($_lists))
{
	return;
}

$_table_data = array();
foreach ($_lists as $key => $item)
{
	$_table_data[$key]['expect_min_return_rate'] = $item['yjkhzdnsyl'];
	$_table_data[$key]['product_end_date'] = $item['cpyjzzrq'];
	$_table_data[$key]['days_count'] = $item['cpqx'];
	$_table_data[$key]['raise_end_date'] = $item['mjjsrq'];
	$_table_data[$key]['product_net_worth'] = $item['bqjz'];
	$_table_data[$key]['benefit_type'] = $item['cpsylxms'];
	$_table_data[$key]['duration_type'] = $item['qxms'];
	$_table_data[$key]['raise_start_date'] = $item['mjqsrq'];
	$_table_data[$key]['raise_currency'] = $item['mjbz'];
	$_table_data[$key]['title'] = $item['cpms'];
	$_table_data[$key]['issued_by'] = $item['fxjgms'];
	$_table_data[$key]['recent_open_start_date'] = $item['kfzqqsr'];
	$_table_data[$key]['init_net_worth'] = $item['csjz'];
	$_table_data[$key]['register_number'] = $item['cpdjbm'];
	$_table_data[$key]['expect_max_return_rate'] = $item['yjkhzgnsyl'];
	$_table_data[$key]['op_model'] = $item['cplxms'];
	$_table_data[$key]['real_return_rate'] = $item['dqsjsyl'];
	$_table_data[$key]['recent_open_end_date'] = $item['kfzqjsr'];
	$_table_data[$key]['risk_level'] = $item['fxdjms'];
	$_table_data[$key]['min_buy_amount'] = $item['qdxsje'];
    $_table_data[$key]['created_at'] = $date_time;
    $_table_data[$key]['updated_at'] = $date_time;

/*
	$_table_data[$key][] = $item['cpxsqy'];
	$_table_data[$key][] = $item['cplx'];
	$_table_data[$key][] = $item['xsqy'];
	$_table_data[$key][] = $item['cpqsrq'];
	$_table_data[$key][] = $item['cpfxdj'];
	$_table_data[$key][] = $item['cpdm']; //产品代码，即id，可用于其他接口
	$_table_data[$key][] = $item['fxjgdm']; // 发行机构代码
	$_table_data[$key][] = $item['cpztms'];
	$_table_data[$key][] = $item['orderby'];
	$_table_data[$key][] = $item['cpjz'];
	$_table_data[$key][] = $item['cpsylx'];
*/
}


$_on_dup_columns = $_columns = array(
	'title',
	'register_number',
	'issued_by',
	'op_model',
	'benefit_type',
	'duration_type',
	'raise_currency',
	'min_buy_amount',
	'risk_level',
	'raise_start_date',
	'raise_end_date',
	'product_start_date',
	'product_end_date',
	'recent_open_start_date',
	'recent_open_end_date',
	'init_net_worth',
	'product_net_worth',
	'expect_min_return_rate',
	'expect_max_return_rate',
	'days_count',
	'real_return_rate',
	'sell_area',
    'created_at',
    'updated_at',
	//'status'

);

real_load_data($_table_data, 'Tt',
                'licai_product', $_columns, $_on_dup_columns, $_mysql);
?>