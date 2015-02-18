<?php namespace Controllers\LockRate;

use View;
use Auth;
use Exchange;
use Input;
use App;
use Redirect;
use Validator;
use Session;
use Recipient;
use Bank;
use Queue;
use Sms;
use Balance;
use Receipt;
use Pincode;
use Controllers\StepController;
use Breadcrumb;
use Request;

/**
 *  汇率锁定控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class LockRateController extends StepController {

    /**
     * 需要进行的步骤
     *
     * @var array
     */
    protected $steps = array('getIndex', 'getMoney', 'getConfirm');

    /**
     * Bank model 
     *
     * @var WeXchange\Model\Bank
     */
    protected $bank;

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';


    public function __construct(Bank $bank)
    {
        parent::__construct(__CLASS__, $this->steps);

        // 用户是否 verifty 验证完毕
        $this->beforeFilter(function() {
            $user = real_user();
            if ($user->profile->status != 'verified')
            {
                return Redirect::to('lockrate');
            }
        }, array('only' => array('getMoney')));

        // 检查汇率锁定是否有效
        $this->beforeFilter('lockRate', array('only' => 
            array('getMoney', 'postMoney', 'getConfirm', 'postConfirm')
        ));

        // 检查是否按步骤进行
        $self = $this;
        $this->beforeFilter(function() use ($self) {
            return $self->stepCheck();
        });
        
        $this->bank = $bank;
    }

    /**
     * 换汇查询页面
     *
     * @return void
     */
    public function getIndex()
    {
        $this->layout->content = View::make('lockrate.lock.index');      
    }

    /**
     * 提交换汇锁定
     *
     * @return Redirect
     */
    public function postIndex()
    {
        $validator = Validator::make(Input::all(), array(
            'amount_have' => 'required|numeric',
            'amount_want' => 'required|numeric',
        ));

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        } 

        $currency_have  = Input::get('currency_have');
        $amount_have    = Input::get('amount_have');
        /**
         * 如果 Wexchange 不支持该币种，但用户 lock rate 了该币种，
         * 但不余额不足以支付，则报错
         */
        $user = real_user();
        $bank = new Bank;
        $wx_currency_arr = $bank->getFundCurrencies();
        $user_balances = $user->balances()->get();
        $not_support_currency_arr = array();
        foreach ($user_balances as $b)
        {
            if ($b->available_amount <= 0 ||
                    in_array($b->currency, $wx_currency_arr))
            {
                continue;
            }
            $not_support_currency_arr[] = array(
                                            'currency' => $b->currency,
                                            'balance' => $b->available_amount
                                            );
        }

        foreach ($not_support_currency_arr as $c_arr)
        {
            if ($c_arr['currency'] == $currency_have &&
                    $amount_have > $c_arr['balance'])
            {
                $msg = "抱歉，您不能使用 " . $currency_have . 
                        " 锁定汇率。因为您的账户余额 (".currencyFormat($currency_have, $c_arr['balance'], true) .
                            ") 不足以支付该笔交易 (".currencyFormat($currency_have, $amount_have, true).').';
                return Redirect::back()->withInput()->with('error', $msg);
            }
            
        }

        // 锁定换汇金额
        Exchange::amount(Input::get('amount_have'));

        $this->stepDone('getIndex');

        return Redirect::to('lockrate/money');
    }

    /**
     * 汇率锁定页面
     * 
     * @return mixed
     */
    public function getMoney()
    {
        // set breadcrumb
        Breadcrumb::map(array('锁定汇率' => url('lockrate')))->append('填写信息');

        $user = real_user();
        // 地址簿
        $this->data['accounts'] = $user->recipientBankAccounts()->where('recipient_banks.currency', 'like', '%' . Exchange::read('lock_currency_want') . '%')->get();

        // 联系人
        $this->data['recipients'] = $user->recipients()->get();

        // 上次转账原因
        $receipt = $user->receipts()->where('type', '!=', 'Deposit')->orderBy('created_at', 'desc')->first();
        $this->data['reason'] = $receipt ? $receipt->last_reason : '';

        // 是否需要刷新汇率？先锁定汇率后登录的情况有可能需要刷新汇率
        if (Session::has('rate_refresh'))
        {
            $this->data['rate_refresh'] = 1;
            Session::forget('rate_refresh');
        }

        // 可能会收取的费用
        $this->data['fee'] = Receipt::accountFee(Exchange::read('lock_currency_have'));
        
        // 支持换汇货币的银行
        $this->data['bank'] = $this->bank->getFundCurrencies();

        $this->layout->content = View::make('lockrate.lock.money', $this->data);              
    } 

    /**
     * 提交换汇信息
     *
     * @return Response
     */
    public function postMoney()
    {
        $messages = array(
            'recipient_id.required_if' => '请选择收款人信息',
            'required_if'              => ':attribute 不能空白'
        );

        $validator = Validator::make(Input::all(), array(
            'transfer_to'       => 'required|in:balance,address,new',
            'account_id'        => 'required_if:transfer_to,address|valid_recipient',
            'country'           => 'required_if:transfer_to,new',
            'bank_name'         => 'required_if:transfer_to,new',
            'branch_name'       => 'required_if:transfer_to,new',
            'bank_street_number'     => 'required_if:transfer_to,new',
            'bank_street'            => 'required_if:transfer_to,new',
            'bank_city'              => 'required_if:transfer_to,new',
            'bank_state'             => 'required_if:transfer_to,new',
            'bank_postcode'          => 'required_if:transfer_to,new',
            'account_bsb'       => 'account_bsb',
            'account_number'    => 'required_if:transfer_to,new',
            'account_name'      => 'required_if:choose_from,new',
            'address'           => 'required_if:choose_from,new',
            'address_2'         => 'required_if:choose_from,new',
            'address_3'         => 'required_if:choose_from,new',
            'postcode'          => 'required_if:choose_from,new',
            'phone'             => 'required_if:choose_from,new',
            'reason'            => 'required_if:transfer_to,new',
            'message'           => 'max:60'
        ), $messages);

        if ($validator->fails())
        {
            // ajax方式提交
            if (Request::ajax()) 
            {
                return $this->push('error', array('msg' => $validator->messages()->first()));
            } 
            return Redirect::to('lockrate/money')->withInput()->withErrors($validator);
        } 

        // 需保存的信息
        $data = array(
            'transfer_to' => Input::get('transfer_to'),
            'recipient'   => Recipient::processing(Input::all()),
            'reason'      => Input::get('reason'),
            'message'     => Input::get('message'),
            'send_sms'    => Input::get('send_sms'),
        );

        // 不是汇进 balance？需收手续费
        $data['fee']['total'] = 0;
        if (Input::get('transfer_to') !== 'balance')
        {
            // 支持换汇的货币
            $this->data['bank'] = $this->bank->getFundCurrencies();

            // 澳币并且存入澳洲的银行账号？
            if ($data['recipient']['country'] != 'Australia' ||
                    Exchange::read('lock_currency_want') != 'AUD') 
            {
                $data['fee'] = Receipt::accountFee(Exchange::read('lock_currency_have'));
            } 
            //$fee = $data['fee']['total'];

            $balance = availableBalance(Exchange::read('lock_currency_have'), FALSE);
            if ($balance < Exchange::read('lock_amount') && ! $this->data['bank'])
            {
                $msg = 'We are apologized that we don\'t support ' . 
                        Exchange::read('lock_currency_have') . 
                        ' for deposit. Would you like to complete this transaction using your balance?';
                
                return Redirect::back()->withInput()->with('error', $msg);           
            }        
        }
    
        // 存入临时信息
        Receipt::sessionStore($data);

        // 完成该步骤
        $this->stepDone('getMoney');

        // ajax方式提交
        if (Request::ajax()) 
        {
            return $this->push('ok');
        }
        return Redirect::to('lockrate/confirm')->withInput();
    }  

    /**
     * 显示确认页面
     *
     * @return void
     */
    public function getConfirm()
    {
        // set breadcrumb
        Breadcrumb::map(
                            array('锁定汇率' => url('lockrate'), 
                                    '填写信息' => url('lockrate/money')
                                    )
                        )->append('确认信息');

        // 上一步骤输入的收款人信息
        $this->data['recipient'] = Receipt::session('recipient');

        $fee_arr = Receipt::session('fee');
        $total_transaction_fee = $fee_arr['total'];

        $currency = Exchange::read('lock_currency_have');
		$this->data['tx_fee_payable_balances'] = payableUserBalance($total_transaction_fee, $currency);

        // 新建收款人?生成4位短信验证码，否则需清除历史生成码
        Receipt::session('transfer_to') == 'new' ?
                    Pincode::create() : Pincode::clear();

        // 支持换汇货币的银行
        $this->data['bank'] = $this->bank->getFundCurrencies();

        // 需付金额
        $this->data['payment'] = Receipt::payment();
        
        $this->data['currency'] = $currency;
        $this->data['total_fee'] = $total_transaction_fee;

        $this->layout->content = View::make('lockrate.lock.confirm', $this->data);              
    }

    /**
     * 提交确认表单
     *
     * @return Redirect
     */
    public function postConfirm()
    {
        $messages = array(
            'payment_method.required' => '请选择支付方法',
            'payment_method.in'       => '请选择支付方法',
            'payment_method.valid_pin' => '验证码无效'            
        );
        
        $valid_add_pin = '';
        if (Receipt::session('transfer_to') == 'new')
        {
            $valid_add_pin = '|valid_pin'; 
        }

        $validator = Validator::make(Input::all(), array(
            'payment_method' => 'required|in:balance,deposit|support_for_deposit' . $valid_add_pin,
            //'deposit_bank'   => 'required',
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // 汇率锁定信息
        $fx = Exchange::lockToArray();

        // 所有操作信息
        $data = Receipt::session();

        // 记录支付方式
        $data['payment_method'] = Input::get('payment_method');

		$fee_arr = array(
					'convert_fee_amount' => Input::get('convert_tx_fee_amount', 0),
					'convert_fee_currency' => Input::get('convert_tx_fee_currency', '') == 'deposit' ? 
                                $fx['lock_currency_have'] : Input::get('convert_tx_fee_currency'),

					'convert_fee_rate' => Input::get('convert_tx_fee_rate', 0),
					'convert_fee_from' => Input::get('convert_tx_fee_currency', '') == 'deposit' ? 'deposit' : 'balance',
						);

        // 记录付款信息
        $data['payment'] = Receipt::payment();

        /**
         * 如果 fee 也选择了 deposit， 则需要deposit
         */
        $fee_deposit = 0;
        if ($fee_arr['convert_fee_from'] == 'deposit')
        {
            $fee_deposit = $fee_arr['convert_fee_amount'];
        }

        // 组成存款信息
        if ($data['payment_method'] === 'deposit')
        {
            $data['deposit'] = array(
                'amount'      => $data['payment']['total'] + $fee_deposit,
                'currency'    => $fx['lock_currency_have'],
                'bankinfo'    => Input::get('deposit_bank', ''),
            );              
        }

        // 支付方式选择了余额但余款不够?
        if ($data['payment_method'] === 'balance' AND $data['payment']['pay'] > 0)
        {
            // 组成存款信息
            $data['deposit'] = array(
                'amount'      => $data['payment']['pay'] + $fee_deposit,
                'currency'    => $fx['lock_currency_have'],
                'bankinfo'    => Input::get('deposit_bank', ''),
            );             
        }

        // 组成换汇信息
        $data['fx'] = $fx;

		$data['fee_total'] = $fee_arr;	

        // 不是存进 balance？则组成汇款信息
        if ($data['transfer_to'] !== 'balance')
        {
            $data['withdraw'] = array(
                'amount'         => round($fx['lock_amount'] * $fx['lock_rate'], 2),
                'currency'       => $fx['lock_currency_want'],
                'recipient'      => $data['recipient'],
                'fee'            => $data['fee'],
                'fee_total'		=> $fee_arr
            );        
        }

        // 生成单据类型
        $receipt_type = sprintf('%sF%s', isset($data['deposit']) ? 'D' : '', isset($data['withdraw']) ? 'W' : '');

        $user = real_user();

        // 创建收据
        $receipt = Receipt::create(array(
            'id'     => rn($receipt_type),
            /**
             * uid      - receipt 所有者的 uid
             * opt_uid  - 操作人员 uid
             *
             * 如果一个 business member 在管理 business 账户，则
             *     uid      = business uid
             *     opt_uid  = business member uid
             *
             * 如果登录用户管理的是自己的账户，则 uid = opt_uid
             * 
             */
            'uid'    => $user->uid,
            'opt_uid'=> Auth::member()->user()->uid,
            'status' => isset($data['deposit']) ? 'Waiting for fund' : 'Processing',
            'data'   => serialize($data)
        ));

        $this->stepDone('getConfirm');

        // set breadcrumb
        $breadcrumb = array('lockrate' => array('锁定汇率' => url('lockrate'), '填写信息' => url('lockrate/money'), '确认信息' => url('lockrate/confirm')));
        Session::flash('breadcrumb', serialize($breadcrumb));

        return Redirect::to('receipt/' . $receipt->id);
    }

    /**
     *　发送手机验证码
     *
     * @return  Response
     */
    public function getPin()
    {
        Session::reflash();

        // 不存在验证码？
        if ( ! $pin = Session::get('sms_pin'))
        {
            return $this->push('error', array('msg' => '验证码无效'));
        }

        $user = real_user();
        // 当前用户的验证手机号码
        if ( ! $mobile = $user->verify->security_mobile)
        {
            return $this->push('error', array('msg' => '您还没有绑定手机，不能使用手机验证'));
        }

        $phone_code = '86';
        if (isset($user->verify->phone_code) && !empty($user->verify->phone_code))
        {
            $phone_code = $user->verify->phone_code;    
        }

        // 发送验证码到手机
        Pincode::send(
            '+' . $phone_code . $mobile,
            '欢迎使用 Anying 换汇系统! 为了保证您交易的安全性，请输入验证码：%s'
        );
        
        return $this->push('ok', array('mobile' => substr_replace($mobile, '****', 3, -3)));
    }

    /**
     * 刷新锁定汇率
     *
     * @return json
     */
    public function getRefresh()
    {  
        $rep = Exchange::refresh();

        return $this->push('ok', $rep);
    }  
}