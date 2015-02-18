<?php namespace WeXchange\Model;

use Session;
use Exchange;
use Transaction;

class Receipt extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receipts';

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
    protected $fillable = array('id', 'uid', 'opt_uid', 'type', 'status', 'data');

    /**
     * 临时保存收据信息的会话名称
     *
     * @var string
     */
    protected static $sessionName = 'receiptSession';

    /**
     * 手续费用列表,均以澳币 AUD 结算
     *
     * @var array
     */
    protected static $fees = array(
        /* 
         * change transaction fee to $15. v0.9 2014-03-02
         * read transaction fee from table TransactionFee, 2014-03-28
         */
        array('name' => 'Transaction Fee', 'amount' => 15),
    );

    /**
     * 返回所属用户模型
     *
     * @return WeXchange\Model\User
     */
    public function user()
    {
        return $this->belongsTo('User', 'uid');
    }

    /**
     * 
     *
     * @return WeXchange\Model\User
     */
    public function op_user()
    {
        return User::find($this->opt_uid);
    }

    /**
     * FX Accessor
     *
     * @return array
     */
    public function getFxAttribute()
    {
         $data = unserialize($this->data);    
         return $data['fx'];
    } 

    /**
     * Amount Accessor
     *
     * @return float
     */
    public function getAmountAttribute()
    {
         $data = unserialize($this->data);

         if ($this->type == 'Withdraw')
         {
         	if (isset($data['withdraw']))
         	{
         		return currencyFormat($data['withdraw']['currency'], $data['withdraw']['amount'], TRUE);    
         	}

         	$total_amount = 0;
         	$currency = '';
         	foreach ($data as $d)
	        {
	        	$currency = $d['withdraw']['currency'];
		        $total_amount += $d['withdraw']['amount'];
	        }

            return currencyFormat($currency, $total_amount, TRUE);            
         }

         if ($this->type == 'Deposit')
         {
            return currencyFormat($data['deposit']['currency'], $data['deposit']['amount'], TRUE);
         }

         return currencyFormat($data['fx']['lock_currency_want'], $data['fx']['lock_amount'] * $data['fx']['lock_rate'], TRUE);
    }

    /**
     * total amount
     *
     * @return float
     */
    public function getAmountTotalAttribute()
    {

    	$ret_arr = array('amount' => 0, 'currency' => '');

		$data = unserialize($this->data);

		if ($this->type == 'Withdraw')
		{
			// 兼容旧数据格式
			if (isset($data['withdraw']))
			{
				$ret_arr['currency'] = $data['withdraw']['currency'];
				$ret_arr['amount'] = $data['withdraw']['amount'];
				return $ret_arr;    
			}

			$total_amount = 0;
			$currency = '';
			foreach ($data as $d)
			{
				$currency = $d['withdraw']['currency'];
		    	$total_amount += $d['withdraw']['amount'];
			}

			$ret_arr['currency'] = $currency;
			$ret_arr['amount'] = $total_amount;
			return $ret_arr;
		}

		if ($this->type == 'Deposit')
		{
			$ret_arr['currency'] = $data['deposit']['currency'];
			$ret_arr['amount'] = $data['deposit']['amount'];
			return $ret_arr;
		}

		$ret_arr['currency'] = $data['fx']['lock_currency_have'];
		$ret_arr['amount'] = $data['fx']['lock_amount'];
		return $ret_arr;
    }

    /**
     * Receipt Fee Total
     *
     * @return string
     */
    public function getFeeTotalAttribute()
    {

        $data = unserialize($this->data); 	

        $fee_currency = '';  
        if ($this->type == 'Withdraw') 
        {
        	// 兼容旧数据格式
        	if (isset($data['fee']['total'])) 
        	{
        		$fee_currency = $data['withdraw']['currency'];
        		return array(
                        'amount' => isset($data['fee']['total']) ? $data['fee']['total'] : 0,
                        'currency' => $fee_currency
                    );         		
        	}

        	// 新数据格式，数组
            $total_fee = 0;
         	foreach ($data as $d)
	        {
	        	$fee_currency = $d['withdraw']['currency'];
		        $total_fee += $d['fee']['total'];
	        }

        	return array(
                        'amount' => $total_fee ,
                        'currency' => $fee_currency
                    ); 

        }
        else if ($this->type == 'Deposit')
        {
            $fee_currency = $data['deposit']['currency'];
        }
        else
        {
            $fee_currency = $data['fx']['lock_currency_have'];
        }

        return array(
                        'amount' => isset($data['fee']['total']) ? $data['fee']['total'] : 0,
                        'currency' => $fee_currency
                    );
    }

    /**
     * Converted Receipt Fee Total
     *
     * @return string
     */
    public function getConvertedFeeTotalAttribute()
    {

    	$ret_arr = array(
							'convert_fee_amount' => 0,
							'convert_fee_currency' => '',
							'convert_fee_rate' => 0,
							'convert_fee_from' => '',
    		);

        $data = unserialize($this->data); 	

        $fee_currency = '';  
        if ($this->type == 'Depoist' || empty($data))
       	{
       		return $ret_arr;
       	} 

       	if ($this->type == 'Withdraw')
       	{
	       	/*
	       	 * withdraw
	       	 * 
	       	 * format:
	       	 * 
	       	 * 	$data[]['fee_total'] = array(
								'convert_fee_amount' => Input::get('convert_tx_fee_amount'),
								'convert_fee_currency' => Input::get('convert_tx_fee_currency'),
								'convert_fee_rate' => Input::get('convert_tx_fee_rate'),
								'convert_fee_from' => 'balance/deposit',
								);
	       	 */
	       	return isset($data[0]['fee_total']) ? $data[0]['fee_total'] : $ret_arr;       		
       	}

       	/*
       	 * lock rate
       	 * 
       	 * format:
       	 * 
       	 * 	$data['fee_total'] = array(
							'convert_fee_amount' => Input::get('convert_tx_fee_amount'),
							'convert_fee_currency' => Input::get('convert_tx_fee_currency'),
							'convert_fee_rate' => Input::get('convert_tx_fee_rate'),
							'convert_fee_from' => '',
							);
       	 */
       	return isset($data['fee_total']) ? $data['fee_total'] : $ret_arr;  

    }

    public function getNeed2DepositAttribute() 
    {
    	$ret_arr = array();

    	$data = unserialize($this->data);
    	$converted_fee_arr = $this->converted_fee_total;
    	$amount_arr = $this->amount_total;
    	$pay = $data['payment']['pay'];

    	switch ($this->type)
    	{
	    	case 'Deposit':
	    	{
	    		$ret_arr['deal'] = $amount_arr;
	    		break;
	    	}

	    	case 'Fx Deal':	    	
	    	case 'Withdraw':
	    	{
	    		return array();
	    	}   
	    	
	    	case 'All' :	
	    	default :
	    	{
	    		if ($converted_fee_arr['convert_fee_from'] == 'deposit')
	    		{
	    			$ret_arr['fee']['amount'] = $converted_fee_arr['convert_fee_amount'];
	    			$ret_arr['fee']['currency'] = $this->fx_currency_from;
	    		}
	    		
	    		$ret_arr['deal']['amount'] = $pay; 
	    		$ret_arr['deal']['currency'] = $this->fx_currency_from;
	    		break;
	    	}

    	}
    	return $ret_arr;
    }

    /**
     * Receipt FX Rate
     *
     * @return string
     */
    public function getFxRateAttribute()
    {
         $data = unserialize($this->data);

         $rate = '';
         if ($this->type == 'Withdraw' || 
         		$this->type == 'Deposit'	||
         		!isset($data['fx']['lock_rate'])
         	)
         {
            return '';            
         }

         return $data['fx']['lock_rate'];
    }

    /**
     * Receipt FX Currency To
     *
     * @return string
     */
    public function getFxCurrencyToAttribute()
    {
         $data = unserialize($this->data);

         if ($this->type == 'Withdraw' || 
         		$this->type == 'Deposit'	||
         		!isset($data['fx']['lock_currency_want'])
         	)
         {
            return '';            
         }

         return $data['fx']['lock_currency_want'];
    }

    /**
     * Receipt FX currency from
     *
     * @return string
     */
    public function getFxCurrencyFromAttribute()
    {
         $data = unserialize($this->data);

         if ($this->type == 'Withdraw' || 
         		$this->type == 'Deposit'	||
         		!isset($data['fx']['lock_currency_have'])
         	)
         {
            return '';            
         }

         return $data['fx']['lock_currency_have'];
    }

    /**
     * TransferTo Accessor
     *
     * @return array
     */
    public function getTransferToAttribute()
    {

        $data = unserialize($this->data);     
        if ($this->type == 'Withdraw')
        {
        	// 兼容旧数据格式
        	if (isset($data['recipient']['account_name']))
        	{
        		return sprintf('%s (%s)', 
        				$data['recipient']['account_name'], 
        				isset($data['recipient']['country']) ? 
        					$data['recipient']['country'] 
        					: 
        					$data['recipient']['transfer_country']
        					);
        	}

        	// 新数据格式，数组
        	$t_to = array();
        	foreach ($data as $d)
        	{
        		$t_to[] = sprintf('%s (%s)', 
	        				$d['recipient']['account_name'], 
	        				isset($d['recipient']['country']) ? 
	        					$d['recipient']['country'] 
	        					: 
	        					$d['recipient']['transfer_country']
	        					);       
        	}
	        return implode($t_to, ', ');
        }

        if ($data['transfer_to'] == 'balance')
        {
            return sprintf('%s Balance', 
            				isset($data['deposit']) ? 
            					$data['deposit']['currency'] 
            					: 
            					$data['fx']['lock_currency_want']
            					);
        }  

        return sprintf('%s (%s)', 
        				$data['recipient']['account_name'], 
        				isset($data['recipient']['country']) ? 
        					$data['recipient']['country'] 
        					: 
        					$data['recipient']['transfer_country']
        					);
    } 

    /**
     * 获取上一次的汇款原因
     *
     * @return array
     */
    public function getLastReasonAttribute()
    {
        $data = unserialize($this->data);

        if ($this->type == 'Withdraw')
        {
        	if (isset($data['reason']))
        	{
        		return $data['reason'];
        	}

        	return isset($data[0]['reason']) ? $data[0]['reason'] : '';
        }

        return isset($data['reason']) ? $data['reason'] : '';
    }

    /**
     * 临时保存信息
     *
     * @param  array
     * @return void
     */
    public static function sessionStore($input)
    {   
        Session::put(self::$sessionName, serialize($input));
    }

    /**
     * 清除临时信息
     *
     * @return void
     */
    public static function clearSessionStore()
    {
        Session::forget(self::$sessionName);
    }


    public static function getSession() 
    {
    	return Session::get(self::$sessionName);
    }
    /**
     * 读取指定信息
     *
     * @return mixed
     */
    public static function session()
    {
        $output = NULL;
        
        $data = unserialize(Session::get(self::$sessionName));

        // 没有传递参数
        if (func_num_args() == 0)
        {
            return $data;
        }

        foreach (func_get_args() as $key =>$arg)
        {
            if ($key == 0)
            {
                $output = isset($data[$arg]) ? $data[$arg] : NULL;
            }
            else
            {
                $output = isset($output[$arg]) ? $output[$arg] : NULL;
            }
        }

        return $output;
    }

    /**
     * 计算手续费
     * 
     * @param  string 需要兑换成 AUD 的货币符号
     * @return array
     */
    public static function accountFee($currency)
    {
        // 总额
        $total = 0;

        // 详细条目
        $items = array();

        $aud_amount = 0;
        $real_amount = 0;

        /**
         * 当前只有一种Transaction Fee, v0.9, 2014-03-31
         */
        $tx_fee = TransactionFee::first();
        if (!is_login())
        {
            /**
             * 没有登录过的用户，默认 Transaction Fee 同普通用户
             */
            $aud_amount = $tx_fee->amount;
        }
        else
        {
            /**
             * current user
             */
            $user = real_user();  
            switch ($user->profile->vip_type)
            {
            case 'Silver':
                $aud_amount = $tx_fee->silver_amount;
                break;
            case 'Golden':
                $aud_amount = $tx_fee->golden_amount;
                break;
            case 'Platinum':
                $aud_amount = $tx_fee->platinum_amount;
                break;
            case 'Basic':   // 普通客户
            default:
                $aud_amount = $tx_fee->amount;
                break;
            }          
        }
        // 用户不是以 AUD 换汇，需将手续费转为当前货币
        $real_amount = round(Exchange::transaction_fee($aud_amount, $currency), 0);

        $items[] = array (
                'name'     => 'Transaction Fee',
                'amount'   => $aud_amount,
                'fee'      => $real_amount,
            );

        return array(
            'total' => $real_amount,
            'items' => $items
        );
    }

    /**
     * 计算需要支付的金额
     *
     *  Lock Rate、FX 时用该方法计算支付金额
     *
     * @return array
     */
    public static function payment()
    {
        // 用户的可用余额
        $balance = availableBalance(Exchange::read('lock_currency_have'), FALSE);

        // 所需要支付金额
        $deposit_total = Exchange::read('lock_amount');    
        //$deposit_total = Exchange::read('lock_amount') + self::session('fee', 'total');

        // 可以从余额支付的金额
        $from_balance = $balance > $deposit_total ?  $deposit_total : $balance;

        // 扣除余额还需支付的金额
        $need_to_pay = $deposit_total - $from_balance;  

        return array(
            'balance' => $balance,       // 用户余额
            'total'   => $deposit_total,  // 需要支付金额
            'deduct'  => $from_balance,   // 可从 balance 扣除的金额
            'pay'     => $need_to_pay      // 扣除 balance 仍需支付的金额
        );            
    }

    /**
     * Get user notes.
     *
     * @return object
     */
    public function notes()
    {
        return $this->hasMany('Notes', 'item_id');
    }

    /**
     * Get related transaction.
     *
     * @param $type
     * @return object
     */
    public function transaction($type = '')
    {
        if ($type) 
        {
            return Transaction::where('receipt_id', '=', $this->id)->where('type', '=', $type)->first();
        }
        return $this->hasMany('Transaction', 'receipt_id')->get();
    }
}