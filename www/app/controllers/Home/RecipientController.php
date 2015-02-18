<?php namespace Controllers\Home;

use App;
use View;
use Auth;
use Session;
use Validator;
use Input;
use Redirect;
use Recipient;
use Request;
use Response;
use Controllers\AuthController;
use Breadcrumb;
use User;
use Rate;
use RecipientBank;

/**
 *  收款人控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class RecipientController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    public function __construct(Rate $rate)
    {
        parent::__construct();
        
        $this->rate = $rate;
    }

    /**
     * 收款人列表
     *
     * @return void
     */
    public function getIndex()
    {
        // set breadcrumb
        Breadcrumb::map(array('我的收款人' => url('home')))->append("A—Z");

        $user = real_user();

        // 收款人
        $this->data['recipients'] = $user
             ->recipients()
             ->orderBy('created_at', 'desc')
             ->get();   

        $this->layout->content = View::make('home.recipient.index', $this->data);
    }  

    /*
     * get json to 'show more'
     */
    public function getJson($page_idx = 1)
    {
        $_page_idx = $page_idx;		
        
        $user = real_user();

        // 收款人
        $_recipients = $user
        ->recipients()
        ->orderBy('created_at', 'desc')
        ->take(parent::DEFAULT_SHOW_MORE_ENTRIES * $_page_idx)->get();   

        $_recipient_total_count = real_user()->recipients()->count();

        /* make json */
        $_aa_data = array();
        foreach ($_recipients as $__recipient)
        {
            $_row = array();

            $_row['id'] = $__recipient->id;
            $_row['account_name'] = $__recipient->account_name;

            if ($__recipient->lastTransfer())
            {
                $_row['amount'] = currencyFormat($__recipient->lastTransfer()->currency, $__recipient->lastTransfer()->amount, TRUE) .
                            '<span class="f13p color-666">(' . 
                                date_word($__recipient->lastTransfer()->created_at) .
                            ')</span>';
            } 
            else
            {
                $_row['amount'] = '$0.00';
            }

            $_row['total'] = currencyFormat('AUD', $__recipient->totalTransfer(), TRUE);

            $_aa_data[] = $_row;
        }
        /*
         * output
         */        
        $output = array(
            "aaData"            => $_aa_data,
            "total_count"       => $_recipient_total_count,
        );

        return json_encode($output);

    } 

    /**
     * 添加收款人
     *
     * @return Redirect
     */
    public function getAdd()
    {
        // set breadcrumb
        Breadcrumb::map(array('我的收款人' => url('recipient')))->append('新建收款人');

        // 生成 pin，添加新收款人时需输入
        Session::put('sms_pin', str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));

        // 支持的货币列表
        $this->data['currencies'] = $this->rate->getCurrencies();        

        $this->layout->content = View::make('home.recipient.add', $this->data);
    } 

    /**
     * 提交收款人
     *
     * @return Redirect
     */
    public function postAdd()
    {
        $messages = array(
            'phone.valid_pin' => '请输入验证码',
            'country.valid_country' => '无效的国家'            
           );

        $validator = Validator::make(Input::all(), array(                    
            'email'          => 'email',
            'country'        => 'required|valid_country',
            'bank_name'      => 'required',
            'branch_name'    => 'required',
            'bank_street_number'     => 'required',
            'bank_street'            => 'required',
            'bank_city'              => 'required',
            'bank_state'             => 'required',
            'bank_postcode'          => 'required',
            'account_bsb'    => 'account_bsb',
            'account_number' => 'required',
            'account_name'   => 'required',
            'address'        => 'required',
            'address_2'      => 'required',
            'address_3'      => 'required',
            'postcode'       => 'required',
            'phone_type'     => 'required',
            'phone'          => 'required|valid_pin'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = real_user();

        // 用户名和电话与数据库匹配的为同一联系人
        $recipient = Recipient::where('uid', $user->uid)
                        ->where('account_name', Input::get('account_name'))
                        ->where('phone', Input::get('phone'))
                        ->first();

        if ( ! $recipient)
        {
            // 根据 phone code 确定收款人国家
            $countries = countries();
            $transfer_country = array_search(Input::get('phone_code'), $countries);

            // 创建收款人
            $recipient = Recipient::create(array(
                'uid'              => $user->uid,
                'email'            => Input::get('email'),
                'transfer_country' => $transfer_country,
                'account_name'     => Input::get('account_name'),
                'address'          => Input::get('address'),
                'address_2'        => Input::get('address_2'),
                'address_3'        => Input::get('address_3'),
                'postcode'         => Input::get('postcode'),
                'phone_code'       => Input::get('phone_code'),
                'phone_type'       => Input::get('phone_type'),
                'phone'            => Input::get('phone'),
            ));
        }
        

        // 创建银行信息
        RecipientBank::create(array(
            'recipient_id'     => $recipient->id,
            'swift_code'       => Input::get('swift_code'),
            'country'          => Input::get('country'),
            'bank_name'        => Input::get('bank_name'),
            'branch_name'      => Input::get('branch_name'),
            'unit_number'      => Input::get('bank_unit_number'),
            'street_number'    => Input::get('bank_street_number'),
            'street'           => Input::get('bank_street'),
            'city'             => Input::get('bank_city'),
            'state'            => Input::get('bank_state'),
            'postcode'         => Input::get('bank_postcode'),
            'account_bsb'      => Input::get('account_bsb'),
            'account_number'   => Input::get('account_number'),
            'currency'         => implode(',', Input::get('currency'))
        ));

        return Redirect::to('recipient/detail/' . $recipient->id);
    }

    /**
     * 查看收款人详情 
     *
     * @return void
     */
    public function getDetail($recipient_id = '')
    {

        $user = real_user();

        $this->data['recipient'] = $user->recipients()->findOrFail($recipient_id);

        // set breadcrumb
        Breadcrumb::map(array('我的收款人' => url('recipient')))->append($this->data['recipient']->account_name . '\'s 详细信息');        

        // 转账到该收款人的流水
        $this->data['trans'] = $this->data['recipient']->transactions();

        // 收款人的银行信息
        $this->data['banks'] = $this->data['recipient']->banks;

        $this->layout->content = View::make('home.recipient.detail', $this->data);
    }

    /**
     * 修改收款人
     *
     * @return void
     */
    public function getEdit($recipient_id = '')
    {
        $user = real_user();

        $this->data['recipient'] = $user->recipients()->findOrFail($recipient_id);

        // set breadcrumb
        Breadcrumb::map(array('我的收款人' => url('recipient')))->append('编辑 ' . $this->data['recipient']->account_name);

        // 生成 pin，添加新收款人时需输入
        Session::put('sms_pin', str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));

        $this->layout->content = View::make('home.recipient.edit', $this->data);
    }

    /**
     * 提交修改收款人
     *
     * @return Redirect 
     */
    public function postEdit($recipient_id = '')
    {
        $messages = array(
            'phone.valid_pin'                => '请输入验证码'
           );

        $validator = Validator::make(Input::all(), array(
            'email'            => 'email',
            'account_name'     => 'required',
            'address'          => 'required',
            'address_2'        => 'required',
            'address_3'        => 'required',
            'postcode'         => 'required',
            'phone_type'       => 'required',
            'phone'            => 'required|valid_pin'
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = real_user();
        $recipient = $user->recipients()->findOrFail($recipient_id);

        // 根据 phone code 确定收款人国家
        $countries = countries();
        $transfer_country = array_search(Input::get('phone_code'), $countries);
            
        $recipient->transfer_country = $transfer_country;
        $recipient->email = Input::get('email');
        $recipient->account_name = Input::get('account_name');
        $recipient->address = Input::get('address');
        $recipient->address_2 = Input::get('address_2');
        $recipient->address_3 = Input::get('address_3');
        $recipient->postcode = Input::get('postcode');
        $recipient->phone_code = Input::get('phone_code');
        $recipient->phone_type = Input::get('phone_type');
        $recipient->phone = Input::get('phone');
        $recipient->save();

        return Redirect::to('recipient/detail/' . $recipient->id);
    }

    /**
     * 删除收款人
     *
     * @return Redirect
     */
    public function getDel($recipient_id = '')
    {
        $user = real_user();

        $recipient = $user->recipients()->findOrFail($recipient_id);

        $recipient->delete();

        return Redirect::to('recipient');
    }

    /**
     * 添加银行信息
     *
     * @param  int $recipient_id
     * @return \View
     */
    public function getAddBank($recipient_id = '')
    {     
        $user = real_user();

        $this->data['recipient'] = $user->recipients()->findOrFail($recipient_id);

        // 生成 pin，添加新收款人时需输入
        Session::put('sms_pin', str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));

        // 支持的货币列表
        $this->data['currencies'] = $this->rate->getCurrencies();        

        return View::make('home.recipient.bank.add', $this->data);        
    }

    /**
     * 提交银行信息
     *
     * @return string
     */
    public function postAddBank()
    {
        $messages = array(
            'sms_pin.valid_pin' => '验证码无效',
            'country.valid_country' => '国家无效'
        );

        $validator = Validator::make(Input::all(), array(
            'recipient_id'   => 'required',      
            'country'        => 'required|valid_country',              
            'bank_name'      => 'required',
            'branch_name'    => 'required',
            'bank_street_number'     => 'required',
            'bank_street'            => 'required',
            'bank_city'              => 'required',
            'bank_state'             => 'required',
            'bank_postcode'          => 'required',
            'account_bsb'    => 'account_bsb',
            'account_number' => 'required',
            'sms_pin'        => 'required|valid_pin'            
        ), $messages);

        if ($validator->fails())
        {
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }

        $user = real_user();
        $recipient = $user->recipients()->findOrFail(Input::get('recipient_id'));

        // 创建银行信息
        RecipientBank::create(array(
            'recipient_id'     => $recipient->id,
            'country'          => Input::get('country'),
            'swift_code'       => Input::get('swift_code'),
            'bank_name'        => Input::get('bank_name'),
            'branch_name'      => Input::get('branch_name'),
            'unit_number'      => Input::get('bank_unit_number'),
            'street_number'    => Input::get('bank_street_number'),
            'street'           => Input::get('bank_street'),
            'city'             => Input::get('bank_city'),
            'state'            => Input::get('bank_state'),
            'postcode'         => Input::get('bank_postcode'),
            'account_bsb'      => Input::get('account_bsb'),
            'account_number'   => Input::get('account_number'),
            'currency'         => implode(',', Input::get('currency'))
        ));

        return $this->push('ok');
    }

    /**
     * 修改银行账号
     *
     * @return \View
     */
    public function getEditBank($bank_id = '')
    {
        $this->data['bank'] = RecipientBank::findOrFail($bank_id);

        // 生成 pin，添加新收款人时需输入
        Session::put('sms_pin', str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));

        // 支持的货币列表
        $this->data['currencies'] = $this->rate->getCurrencies();        

        return View::make('home.recipient.bank.edit', $this->data);        
    }

    /**
     * 提交银行账号修改
     *
     * @param  int
     * @return string
     */
    public function postEditBank($bank_id = '')
    {
        $messages = array(
            'sms_pin.valid_pin' => '请输入验证码',
            'country.valid_country' => '国家无效'
        );

        $validator = Validator::make(Input::all(), array(
            'country'        => 'required|valid_country',
            'bank_name'      => 'required',
            'branch_name'    => 'required',
            'bank_street_number'     => 'required',
            'bank_street'            => 'required',
            'bank_city'              => 'required',
            'bank_state'             => 'required',
            'bank_postcode'          => 'required',
            'account_bsb'    => 'account_bsb',
            'account_number' => 'required',
            'sms_pin'        => 'required|valid_pin'            
        ), $messages);

        if ($validator->fails())
        {
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }

        $bank = RecipientBank::findOrFail($bank_id);

        $user = real_user();
        // 该银行账号不属于当前用户?
        if ($bank->recipient->uid != $user->uid)
        {
            return $this->push('error');
        }        

        // 修改银行账户信息
        $bank->country      = Input::get('country');
        $bank->swift_code   = Input::get('swift_code');
        $bank->bank_name    = Input::get('bank_name');
        $bank->branch_name  = Input::get('branch_name');
        $bank->unit_number  = Input::get('bank_unit_number');
        $bank->street_number= Input::get('bank_street_number');
        $bank->street       = Input::get('bank_street');
        $bank->city         = Input::get('bank_city');
        $bank->state        = Input::get('bank_state');
        $bank->postcode     = Input::get('bank_postcode');
        $bank->account_bsb  = Input::get('account_bsb');
        $bank->account_number = Input::get('account_number');
        $bank->currency     = implode(',', Input::get('currency'));
        $bank->save();

        return $this->push('ok');        
    }

    /**
     * 删除银行账户
     *
     * @param  int
     * @return \Redirect
     */
    public function getDelBank($bank_id = '')
    {
        $bank = RecipientBank::findOrFail($bank_id);

        $recipient_id = $bank->recipient->id;

        $user = real_user();
        if ($bank->recipient->uid == $user->uid)
        {
            $bank->delete();
        }

        return Redirect::to('recipient/detail/' . $recipient_id);
    }

    public function getRecipientBankAccount($currency = 'AUD')
    {
    	$result = array();

        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

    	$user = real_user();

        $recipient_accounts = $user->recipientBankAccounts()
        					->where('recipient_banks.currency', 'like', '%' . $currency . '%')
        					->get();

        foreach ($recipient_accounts as $account) 
        {
            $result[] = array(
            				'value' => $account->id, 
            				'text' => sprintf('%s %s %s - %s', 
            											$account->account_name, 
            											$account->account_number,
            											$account->bank_name,
            											$account->branch_name)
            				);
        }        

        return $this->push('ok', array('data' => $result)); 

    }

	public function getRecipient()
    {
    	$result = array();

        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

    	$user = real_user();

        $recipients = $user->recipients()->get();

        foreach ($recipients as $r) 
        {
            $result[] = array(
            				'value' => $r->id, 
            				'text' => sprintf('%s (+%s %s)', 
            									$r->account_name, 
            									$r->phone_code, 
            									$r->phone)
            				);
        }        

        return $this->push('ok', array('data' => $result)); 

    }


    public function getPhoneCode($currency)
    {
        $result = array();
        $currency_country = currencyCountry($currency);

        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        foreach (countries() as $country => $code)
        {

            $selected = $currency_country == $country ? 'selected' : '';
            $result[] = array(
                            'value' => $code, 
                            'text' => sprintf('%s (+%s)', $country, $code),
                            'data-country'  => $country,
                            );

        }

        return $this->push('ok', array('data' => $result)); 

    }
}