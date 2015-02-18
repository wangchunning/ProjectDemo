<?php namespace WeXchange\Model;

use Recipient;
use Auth;
use Transaction;
use Exchange;
use BalanceHistory;
use Sms;
use Queue;
use RecipientBank;

use Redirect;

class ReceiptObserver {

    /**
     * 收据号
     *
     * @var int
     */
    private $receipt_id;

    /**
     * 类型
     *
     * @var string
     */
    private $type;

    /**
     * 创建收据时触发
     *
     * @param  WeXchange\Model\Receipt
     * @return void
     */
    public function created($receipt)
    {
        $this->receipt_id = $receipt->id;

        $this->type = $receipt->type;

        $data = unserialize($receipt->data);

        if ($this->type == 'Withdraw')
        {
        	foreach ($data as $d)
        	{
		        $this->createRecipient($d);
		        $this->createTransaction($d);
		        $this->paymentProcessing($d);
        	}        	
        }
        else
        {
	        // 创建收款人
	        $this->createRecipient($data);
	        // 创建流水单
	        $this->createTransaction($data);
	        // 支付处理
	        $this->paymentProcessing($data);
        }

        // 清除临时信息
        Receipt::clearSessionStore();

        // 清除锁定的汇率信息
        Exchange::clear();
    }

    /**
     * 更新收据时触发
     *
     *  完成 Withdraw 后发送短信通知给收款人
     * 
     * @param  WeXchange\Model\Receipt
     * @return void
     */
    public function updated($receipt)
    {
        // 不是 Withdraw 不需要短信通知
        if (in_array($receipt->type, array('Deposit', 'FX Deal')))
        {
            return;
        }

        // 未完成不需要短信通知
        if ($receipt->status != 'Completed')
        {
            return;
        }

        $data = unserialize($receipt->data);

        // 不需要短信通知？
        //if ( ! $data[0]['send_sms'] OR ! isset($data['recipient']['phone']))
        // 兼容旧数据格式
        // 新数据格式
        if (isset($data[0]['send_sms']))
        {
            if (!$data[0]['send_sms'])
            {
                return;
            }

            foreach ($data as $d)
            {
                if (! isset($d['recipient']['phone']))
                {
                    continue;
                }

                // 接收短信的手机号码
                $phone = sprintf('+%s%s', $d['recipient']['phone_code'], $d['recipient']['phone']);

                // 短信内容
                $message = sprintf(
                    $d['recipient']['phone_code'] == '86' ? '%s 在 Anying 转了%s给你，很快就会收到了！' : '%s had transferred %s to you on the Anying, it is on the way!',
                    $receipt->user->full_name,
                    currencyFormat($d['withdraw']['currency'], $d['withdraw']['amount'], TRUE)
                );

                // 短信结构
                $sms = array(
                    'to'   => $phone,
                    'text' => $message
                );

                // 推到队列发送
                Queue::push(function($job) use ($sms)
                {
                    Sms::send($sms);
                    
                    $job->delete();
                }); 
            } 
        }
        else
        {
            if (!$data['send_sms'] || !isset($data['recipient']['phone']))
            {
                return;
            }

            // 接收短信的手机号码
            $phone = sprintf('+%s%s', $data['recipient']['phone_code'], $data['recipient']['phone']);

            // 短信内容
            $message = sprintf(
                $data['recipient']['phone_code'] == '86' ? '%s 在 Anying 转了%s给你，很快就会收到了！' : '%s had transferred %s to you on the Anying, it is on the way!',
                $receipt->user->full_name,
                currencyFormat($data['withdraw']['currency'], $data['withdraw']['amount'], TRUE)
            );

            // 短信结构
            $sms = array(
                'to'   => $phone,
                'text' => $message
            );

            // 推到队列发送
            Queue::push(function($job) use ($sms)
            {
                Sms::send($sms);
                
                $job->delete();
            }); 

        }

    }

    /**
     * 创建收款人
     *
     * @param  array
     * @return void
     */
    private function createRecipient($data)
    {
        // 用户选择不保存到地址簿？
        if ( ! isset($data['recipient']['save_to_book']) ||
        		$data['transfer_to'] == 'address')
        {
            return;
        }

        $user = real_user();
        // 用户名和电话与数据库匹配的为同一联系人
        $recipient = Recipient::where('uid', $user->uid)
                        ->where('account_name', $data['recipient']['account_name'])
                        ->where('phone', $data['recipient']['phone'])
                        ->first();

        if ( ! $recipient) 
        {
            $recipient = new Recipient;
            // 创建收款人
            foreach ($recipient->getFillable() as $key)
            {
                if ($key == 'uid') 
                {
                    $new[$key] = $user->uid;
                }
                else if (isset($data['recipient'][$key])) 
                {
                    $new[$key] = $data['recipient'][$key];
                }           
            }
            $recipient = Recipient::create($new);
        }

        // 创建收款人银行账户
        RecipientBank::create(array(
            'recipient_id' => $recipient->id,
            'swift_code'   => $data['recipient']['swift_code'],
            'country'      => isset($data['recipient']['country']) ? $data['recipient']['country'] : $data['recipient']['transfer_country'],
            'bank_name'    => $data['recipient']['bank_name'],
            'branch_name'  => $data['recipient']['branch_name'],
            //'bank_address' => isset($data['recipient']['bank_address']) ? $data['recipient']['bank_address'] : '',
            'unit_number'  => $data['recipient']['bank_unit_number'],
            'street_number'    => $data['recipient']['bank_street_number'],
            'street'           => $data['recipient']['bank_street'],
            'city'             => $data['recipient']['bank_city'],
            'state'            => $data['recipient']['bank_state'],
            'postcode'         => $data['recipient']['bank_postcode'],            
            'account_bsb'      => $data['recipient']['account_bsb'],
            'account_number' => $data['recipient']['account_number'],
            'currency'       => $data['recipient']['currency']
        ));
    }

    /**
     * 创建流水单
     *
     *
     * @param  array
     * @return void
     */
    private function createTransaction($data)
    {
        $user = real_user();
        // 创建 deposit 流水单?
        if (isset($data['deposit']))
        {
            $transaction = Transaction::create(array(
                'id'         => tn('D'),
                'uid'        => $user->uid,
                'type'       => 'Deposit',
                'status'     => 'Waiting for fund',
                'amount'     => $data['deposit']['amount'],
                'currency'   => $data['deposit']['currency'],
                'data'       => serialize($data['deposit']),
                'receipt_id' => $this->receipt_id,
            ));

            // 创建汇款银行信息
            $bank_id = $data['deposit']['bankinfo'];
            if ($bank_id != -1)
            {
                TransactionBank::create(array(
                    'bank_id'           => $bank_id,
                    'transaction_id'    => $transaction->id,
                    'currency'          => $transaction->currency,
                    'amount'            => $data['deposit']['amount']
                ));                
            }

            // 同时创建balance account
            $balance = balanceAccount($data['deposit']['currency']);
        }

        // 创建 fx 流水单?
        if (isset($data['fx']))
        {
            Transaction::create(array(
                'id'            => tn('F'),
                'uid'           => $user->uid,
                'type'          => 'FX Deal',
                'status'        => isset($data['deposit']) ? 'Pending' : 'Waiting for fund',
                'amount'        => $data['fx']['lock_amount'],
                'currency_from' => $data['fx']['lock_currency_have'],
                'currency_to'   => $data['fx']['lock_currency_want'],
                'rate'          => $data['fx']['lock_rate'],
                'currency'      => $data['fx']['lock_currency_have'],
                'data'          => serialize($data['fx']),
                'receipt_id'    => $this->receipt_id,
            ));
        }

        // 创建 withdraw 流水单?
        if (isset($data['withdraw']))
        {
            $transaction = Transaction::create(array(
                'id'             => tn('W'),
                'uid'            => $user->uid,
                'type'           => 'withdraw',
                'status'         => 'Pending',
                'amount'         => $data['withdraw']['amount'],
                'currency'       => $data['withdraw']['currency'],
                'data'           => serialize($data['withdraw']),
                'receipt_id'     => $this->receipt_id,
                'account_number' => $data['recipient']['account_number']
            ));

            // 创建withdraw银行信息，辅助管理员操作
            $bankinfo = autoWithdrawBank($data['withdraw']);
            if (is_array($bankinfo)) 
            {
                foreach ($bankinfo as $b) 
                {
                    TransactionBank::create(array(
                        'bank_id'           => $b['id'],
                        'transaction_id'    => $transaction->id,
                        'currency'          => $transaction->currency,
                        'amount'            => $b['amount']
                    ));
                }
            }                         
        }
    }

    /**
     * 支付处理
     *
     * @param array
     * @return void
     */
    private function paymentProcessing($data)
    {		

        if ($this->type == 'Deposit')
        {
            return;
        }
        
        $converted_fee_arr = $data['fee_total'];
        // 用户选择的是 deposit 支付？
        if  ($data['payment_method'] == 'deposit' &&
                $converted_fee_arr['convert_fee_from'] == 'deposit')
        {
            return;
        }   


        $fee_amount = round($data['fee']['total'] * $converted_fee_arr['convert_fee_rate'], 0);

        // 冻结 fee 
        if ($fee_amount > 0)
        {
            $balance = balanceAccount($converted_fee_arr['convert_fee_currency']);
            $balance->increment('frozen_amount', $fee_amount);
        }
        // Withdraw 类型？
        if ($this->type == 'Withdraw')
        {
            // 冻结余额账号的支付金额
            $balance = balanceAccount($data['currency']);
            $balance->increment('frozen_amount', $data['amount']);

            return;
        }

        // 支付信息
        $payment = $data['payment'];

        // 换汇信息
        $fx = $data['fx'];

        $user = real_user();
        // 余额足够支付
        if ($data['payment_method'] != 'deposit' && $payment['pay'] == 0)
        {
            // 是否需要手续费
            $fee = $data['fee']['total'];

            // 减少支付账号的余额
            $balance = balanceAccount($fx['lock_currency_have']);
            BalanceHistory::create(array(
                'uid'        => $user->uid,
                'currency'   => $fx['lock_currency_have'],
                'pre_amount' => $balance->amount,
                'amount'     => '-' . $payment['total'],
                'receipt_id' => $this->receipt_id
            ));
            $balance->decrement('amount', $payment['total']);

            // 增加收入账号的余额
            $balance = balanceAccount($fx['lock_currency_want']);
            BalanceHistory::create(array(
                'uid'        => $user->uid,
                'currency'   => $fx['lock_currency_want'],
                'pre_amount' => $balance->amount,
                'amount'     => $fx['lock_amount'] * $fx['lock_rate'],
                'receipt_id' => $this->receipt_id
            ));
            $balance->increment('amount', $fx['lock_amount'] * $fx['lock_rate']);

            // transaction 状态修改为完成
            Transaction::where('receipt_id', '=', $this->receipt_id)
                ->where('type', '=', 'FX Deal')
                ->update(array(
                'status' => 'Completed'
            ));

            // 汇入余额账号? 
            if ($data['transfer_to'] == 'balance')
            {
                // receipt 状态为完成
                Receipt::find($this->receipt_id)->update(array(
                    'status' => 'Completed',
                    'type'   => 'FX Deal'
                ));
            }
            // 汇给收款人
            else
            {
                // 冻结汇出金额，等后台人员审核 withdraw 
                $balance = balanceAccount($fx['lock_currency_want']);
                $balance->increment('frozen_amount', $fx['lock_amount'] * $fx['lock_rate']);               
            }

            return;
        }

        // 余额不够完全支付
        if ($data['payment_method'] != 'deposit' && $payment['pay'] > 0)
        {
            // 冻结余额账号的支付金额
            $balance = balanceAccount($fx['lock_currency_have']);
            $balance->increment('frozen_amount', $payment['deduct']);

            return;            
        }
    }
}