<?php namespace Controllers\Admin;

use View;
use Validator;
use Redirect;
use Bank;
use Auth;
use BankHistory;
use Activity;
use Input;
use Rate;
use Controllers\AdminController;
use Request;
use App;
use SwiftCode;

/**
 *  银行管理控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class BankController extends AdminController {

    /**
     * Bank model
     *
     * @var WeXchange\Model\Bank
     */
    protected $bank;

    public function __construct(Bank $bank, Rate $rate)
    {
        parent::__construct();

        $this->bank = $bank;

        $this->rate = $rate;

        // 银行可选择的货币列表
        $this->data['currencies'] = $this->rate->getCurrencies();        
    }

    /**
     * 显示银行列表
     *
     * @param  string   $currency 
     * @return void
     */
    public function getIndex($currency = 'AUD')
    {
        // 权限检查
        check_perms('view_bank');

        // 货币过滤标签
        $this->data['filters'] = $this->bank->getCurrencies();

        $this->data['banks'] = $this->bank->where('currency', 'like', '%' . $currency . '%')->get();

        $this->data['current_c'] = $currency;

        $this->layout->content = View::make('admin.center.bank.index', $this->data);
    }

    /**
     * 显示银行流水
     *
     * @param  int   $id
     * @param  string   $currency  
     * @return void
     */
    public function getLog($id, $currency)
    {
        // 权限检查
        check_perm('view_bank');

        $this->data['bank'] = $this->bank->findOrFail($id);
        $this->data['currency'] = $currency;
        // 银行流水
        $this->data['logs'] = $this->data['bank']
             ->logs()
             ->where('currency', $currency)
             ->orderBy('created_at', 'desc')
             ->paginate(50);

        $this->layout->content = View::make('admin.center.bank.log', $this->data);
    }

    /**
     * 显示银行添加页面
     *
     * @return Response
     */
    public function getAdd()
    {
        // 权限检查
        check_perms('view_bank,add_bank');

        $this->data['sub_title'] = 'Add a Bank';

        $this->layout->content = View::make('admin.center.bank.add', $this->data);
    }

    /**
     * 提交银行信息验证
     * 
     *
     * @return Response
     */
    public function postAdd()
    { 
        // 权限检查
        check_perms('view_bank,add_bank');

        $validator = Validator::make(Input::all(), array(
            'country'           => 'required',
            'swift_code'        => 'exists:swift_code',
            'bank'              => 'required',
            'branch'            => 'required',
            'street_number'     => 'required',
            'street'            => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'postcode'          => 'required',
            'account'           => 'required',
            'account_number'    => 'requiredwithout:bank,westpac',
            'BSB'               => 'bank_account_bsb',
            'currency'          => 'required',
            'status'            => 'required'
        ));

        if ($validator->fails())
        {
            return Redirect::to('admin/bank/add')->withInput()->withErrors($validator);
        }

        // 所选的银行不是 Westpac 或者 货币没有AUD
        if (Input::get('as_customer_num') AND (Input::get('bank') != 'Westpac' OR ! in_array('AUD', Input::get('currency')))) 
        {
            return Redirect::to('admin/bank/add')->withInput()->withErrors('The feature is not supported!');
        }

        $this->bank->currency = implode('|', Input::get('currency'));
        $this->bank->limit = (float) str_replace(',', '', Input::get('limit'));

        foreach (array('country', 'swift_code', 'bank', 'branch', 'unit_number', 'street_number', 'street', 'city', 'state', 'postcode', 'account', 'account_number', 'as_customer_num', 'BSB', 'status') as $field)
        {
            $this->bank->$field = trim(Input::get($field));   
        }
        $this->bank->save();

        // add log
        Activity::log(array(
            'obj_id'   => $this->bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Add',
            'description' => 'Add new bank account ' . sprintf('%s(%s)', $this->bank->bank, $this->bank->branch)
        ));

        return Redirect::to('admin/bank');
    }

    /**
     * 显示银行信息
     *
     * @param  string   $id  
     * @return Response
     */
    public function getEdit($id)
    {
        // 权限检查
        check_perms('view_bank');

        $this->data['bank_info'] = $this->bank->findOrFail($id);

        $this->data['sub_title'] = 'Edit Bank';

        $this->layout->content = View::make('admin.center.bank.add', $this->data);
    }

    /**
     * 修改银行信息验证
     * 
     *
     * @param  string   $id
     * @return Response
     */
    public function postEdit($id)
    {
        // 权限检查
        check_perms('view_bank,edit_bank');

        $bank = $this->bank->find($id);

        if (! $bank) return Redirect::back()->with('error', 'The Bank account is not exists!');

        $this->bank = &$bank;

        $validator = Validator::make(Input::all(), array(
            'country'           => 'required',
            'swift_code'        => 'exists:swift_code',
            'bank'              => 'required',
            'branch'            => 'required',
            'street_number'     => 'required',
            'street'            => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'postcode'          => 'required',
            'account'           => 'required',
            'account_number'    => 'requiredwithout:bank,westpac',            
            'BSB'               => 'bank_account_bsb',
            'currency'          => 'required',
            'status'            => 'required'
        ));

        if ($validator->fails())
        {
            return Redirect::to("admin/bank/edit/{$this->bank->id}")->with(array('sub_title' => 'Edit Bank', 'bank_info' => $bank))->withInput()->withErrors($validator);
        }

        // 所选的银行不是 Westpac 或者 货币没有AUD
        if (Input::get('as_customer_num') AND (Input::get('bank') != 'Westpac' OR ! in_array('AUD', Input::get('currency')))) 
        {
            return Redirect::to('admin/bank/add')->withInput()->withErrors('The feature is not supported!');
        }

        $this->bank->currency = implode('|', Input::get('currency'));
        $this->bank->limit = (float) str_replace(',', '', Input::get('limit'));

        foreach (array('country', 'swift_code', 'bank', 'branch', 'unit_number', 'street_number', 'street', 'city', 'state', 'postcode', 'account', 'account_number', 'as_customer_num', 'BSB', 'status') as $field)
        {
            $this->bank->$field = trim(Input::get($field));   
        }
        $this->bank->save();

        // add log
        Activity::log(array(
            'obj_id'   => $this->bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Updated',
            'description' => 'Updated bank account ' . sprintf('%s(%s)', $this->bank->bank, $this->bank->branch) . ' info'
        ));

        return Redirect::to('admin/bank');
    }

    /**
     * 修改银行余额
     * 
     *
     * @param  string   $id
     * @return Response
     */
    public function postEditAmount($id)
    {
        // 权限检查
        check_perms('view_bank,edit_bank_amount');

        $bank = $this->bank->find($id);

        if (! $bank) return $this->push('error', array('msg' => 'The Bank account is not exists!'));

        $currency = Input::get('currency');
        $amount = Input::get('amount') ? Input::get('amount') : 0;

        $old_amount = $bank->getAmount($currency);

        $data = array(
            'bank_id'        => $bank->id,
            'currency'       => $currency,
            'amount'         => $amount,   
            'current_amount' => $amount,
            'transaction_id' => 'manual_oper',
            'memo'           => 'Manager ' . sprintf('<a class="underline" href="/admin/logs?uid=%s">%s</a>', Auth::admin()->user()->uid, Auth::admin()->user()->full_name) . ' change from ' . $old_amount . ' to ' . $amount   
        );
        
        $item_id = BankHistory::create($data);

        // add log
        Activity::log(array(
            'obj_id'   => $bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Updated',
            'description' => 'Change bank ' . sprintf('%s(%s) ', $bank->bank, $bank->branch) . $currency . ' Account Amount from ' . $old_amount . ' to ' . $amount
        ));

        return $this->push('ok', array('amount' => $amount, 'amount_format' => currencyFormat($currency, $amount, true)));
    }

    /**
     * 移除银行帐号
     *
     * @return Response
     */
    public function postRemove()
    {
        // 权限检查
        check_perms('view_bank,delete_bank');

        $bank = $this->bank->find(Input::get('currency'));

        $data = array(
            'obj_id'   => $bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Deleted',
            'description' => 'Deleted bank account ' . sprintf('%s(%s)', $bank->bank, $bank->branch)
        );

        $bank->delete();

        // add log
        Activity::log($data);
        
        return $this->push('ok');        
    }

    /**
     * 修改银行账号状态
     *
     * @return Response
     */
    public function postChangeStatus()
    {
        // 权限检查
        check_perms('view_bank,edit_bank');

        $bank = $this->bank->find(Input::get('id'));

        if ( ! $bank) 
        {
            return $this->push('error', array('msg' => 'The Bank account is not exists!'));
        }

        $bank->status = Input::get('status') == 'true' ? 1 : 0;
        $bank->save();

        $data = array(
            'obj_id'   => $bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Updated',
            'description'   => 'Change bank account ' . sprintf('%s(%s)', $bank->bank, $bank->branch) . ' to ' . ($bank->status == 1 ? 'Live' : 'Off')
        );

        // add log
        Activity::log($data);
        
        return $this->push('ok');        
    }


    public function getBankCurrency($bank_id = NULL)
    {
        $result = array();

        // 非ajax请求？

        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        $bank = Bank::find($bank_id); 
        
        if (empty($bank))
        {
            return $this->push('error');
        }  
        
        $currency_arr = explode('|', $bank->currency);
        foreach ($currency_arr as $currency) 
        {
            $result[] = array('value' => $currency, 'text' => $currency);
        }        

        return $this->push('ok', array('data' => $result));      
    } 

    public function postAddBankLog($bank_id, $currency)
    {
        // 非ajax请求？
        if (!Request::ajax())
        {
            App::abort(404, 'Page not found');
        }

        // 权限检查
        check_perms('view_bank,edit_bank_amount');

        $bank = $this->bank->find($bank_id);

        if (! $bank) 
        {
            return $this->push('error', array('msg' => 'The Bank account is not exists!'));
        }

        $type = Input::get('type');
        $desc = Input::get('desc');

        $amount = trim(Input::get('amount'));  
        if ($type == 'withdraw' )
        {
            $amount = - $amount;  
        } 
        
        $old_amount = $bank->getAmount($currency);

        $data = array(
            'bank_id'        => $bank->id,
            'currency'       => $currency,
            'amount'         => $amount,   
            'current_amount' => $old_amount + $amount,
            'transaction_id' => 'manual_oper',
            'memo'           => $desc  
        );
        
        $item_id = BankHistory::create($data);

        // add log
        Activity::log(array(
            'obj_id'   => $bank->id,
            'activity_type' => 'Banks',
            'action'        => 'Updated',
            'description' => Auth::admin()->user()->full_name . ' add bank log' . sprintf('%s(%s) ', $bank->bank, $bank->branch) . $currency . '. Account Amount from ' . $old_amount . ' to ' . $amount
        ));

        return $this->push('ok');                
    }   
}
