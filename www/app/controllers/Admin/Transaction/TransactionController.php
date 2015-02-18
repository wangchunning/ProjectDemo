<?php namespace Controllers\Admin;

use DB;
use View;
use Transaction;
use Receipt;
use Redirect;
use Input;
use Request;
use Activity;
use Notes;
use Auth;
use User;
use Controllers\AdminController;
use Mail;
use Bank;
use TransactionBank;
use Validator;

/**
 *  Transaction 管理控制器
 *
 *  @author     Pumpkin <pob986@163.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class TransactionController extends AdminController {

    /**
     * Transaction model
     *
     * @var WeXchange\Model\Transaction
     */
    protected $tran;

    // transaction 为以下状态时不可操作
    protected $final = array('Completed', 'Canceled', 'Voided');

    public function __construct(Transaction $tran)
    {
        parent::__construct();

        $this->tran = $tran;
    }

    /**
     * 显示用户Transaction记录列表
     *
     * @param string $type
     * @return void
     */
    public function getIndex($type = NULL)
    {
        // 权限检查
        check_perms('view_add_fund,view_withdraw,view_fx', 'OR');
        trans_perm($type);

        // 没有指定type
        if ( ! $type AND ! check_perms('view_add_fund,view_withdraw,view_fx', 'AND', FALSE))
        {
            if (check_perm('view_add_fund', FALSE)) 
            {
                $type = 'Deposit';
            }
            elseif (check_perm('view_fx', FALSE)) 
            {
                $type = 'FX Deal';
            }
            elseif (check_perm('view_withdraw', FALSE)) 
            {
                $type = 'Withdraw';
            }
        }

    	$uname = '';
    	if (Input::has('uid'))
    	{
         	$user = User::where('uid', Input::get('uid'))->first();
        	$uname = $user->first_name;   		
    	}

        $args = array();
        $transactions = $this->tran
            ->where(function($q) use (&$args, $type)
            {
                if ($type) $q->where('transactions.type', '=', $type); 

                $query = Request::query();
                foreach ($query as $k => $v) 
                {
                    // 翻页标识
                    if ($k == 'page') continue;

                    // 查询的值为空
                    if ( ! $v) continue;

                    switch ($k) 
                    {
                        case 'start':
                            $q->where('transactions.created_at', '>=', time_to_search(urldecode($v)));
                            break;

                        case 'expriy':
                            $q->where('transactions.created_at', '<=', time_to_search(urldecode($v)));
                            break;

                        case 'dw':
                            $q->where('transactions.created_at', '>', word_to_date($v));
                            break;

                        case 'status':
                            if ($v == 'Overdue') 
                            {
                                $q->where('transactions.status', '=', 'Waiting for fund')->where('transactions.created_at', '<', date('Y-m-d H:i:s', time() - 172800));
                                break; 
                            }
                            $q->where('transactions.'.$k, '=', urldecode($v));
                            break;

                        case 'search':
                        	break;

                        default:
                            $q->where('transactions.'.$k, '=', urldecode($v));
                            break;
                    }
                                      
                    // 记录当前查询字段
                    $args[$k] = $v;
                }
            })
            ->orderBy('transactions.created_at', 'desc');

		if (Input::has('search'))
		{
			$search = Input::get('search');
			$transactions = $transactions->join('users', 'users.uid', '=', 'transactions.uid');
	        $transactions = $transactions->where(function($q) use ($search)
	        {
	            $q->where(function($q) use ($search)
		        {
					$q->whereRaw("concat(users.first_name, ' ',users.last_name) like ?", array("%".$search."%"))
						->orWhere('transactions.receipt_id', 'like', '%'.$search.'%');		
		        });

	        });	
		}

		$total_count = $transactions->count();

        $transactions = $transactions
        		->select('transactions.*')
        		->take(parent::DEFAULT_SHOW_MORE_ENTRIES)->get();

        $this->layout->content = View::make('admin.transaction.index')
            ->with(array(
                'transactions' => $transactions,
                'status' => array('Waiting for fund', 'Pending', 'Overdue', 'Completed', 'Voided', 'Canceled'),
                'args' => $args, 
                'type' => $type,
                'total_count'   => $total_count,
                'uname'	=> $uname,
                ));
    }

    /**
     * for ajax, send json to view
     *
     * @param string $type
     * @return void
     */
    public function getJson($type = 'all', $page_idx = 1)
    {
        $_page_idx = $page_idx;

        $type = $type == 'all' ? '' : $type;

        $args = array();
        $transactions = $this->tran
            ->where(function($q) use (&$args, $type)
            {
                if ($type) $q->where('transactions.type', '=', $type); 

                $query = Request::query();
                foreach ($query as $k => $v) 
                {
                    // 翻页标识
                    if ($k == 'page') continue;

                    // 查询的值为空
                    if ( ! $v) continue;

                    switch ($k) 
                    {
                        case 'start':
                            $q->where('transactions.created_at', '>=', time_to_search(urldecode($v)));
                            break;

                        case 'expriy':
                            $q->where('transactions.created_at', '<=', time_to_search(urldecode($v)));
                            break;

                        case 'dw':
                            $q->where('transactions.created_at', '>', word_to_date($v));
                            break;

                        case 'status':
                            if ($v == 'Overdue') 
                            {
                                $q->where('transactions.status', '=', 'Waiting for fund')->where('transactions.created_at', '<', date('Y-m-d H:i:s', time() - 172800));
                                break; 
                            }
                            $q->where('transactions.'.$k, '=', urldecode($v));
                            break;

                        case 'search':
                        	break;

                        default:
                            $q->where('transactions.'.$k, '=', urldecode($v));
                            break;
                    }
                                      
                    // 记录当前查询字段
                    $args[$k] = $v;
                }
            })
            ->orderBy('transactions.created_at', 'desc');

		if (Input::has('search'))
		{
			$search = Input::get('search');
			$transactions = $transactions->join('users', 'users.uid', '=', 'transactions.uid');
	        $transactions = $transactions->where(function($q) use ($search)
	        {
	            $q->where(function($q) use ($search)
		        {
					$q->whereRaw("concat(users.first_name, ' ',users.last_name) like ?", array("%".$search."%"))
						->orWhere('transactions.receipt_id', 'like', '%'.$search.'%');		
		        });

	        });	
		}

        $total_count = $transactions->count();
        $transactions = $transactions
        		->select('transactions.*')
        		->take(parent::DEFAULT_SHOW_MORE_ENTRIES)->offset(parent::DEFAULT_SHOW_MORE_ENTRIES * ($_page_idx - 1))
        		->get();

        /* make json */
        $_aa_data = array();
        foreach ($transactions as $tran)
        {
            $_row = array();

            $_row[] = date_word($tran->created_at);

            $_row[] = link_to(current_url() . '?uid='.$tran->uid, pretty_str($tran->user->full_name));

            $_row[] = link_to('/admin/transaction/detail/'.$tran->id, $tran->receipt_id);

            switch ($type) 
            {
                case 'Deposit':
                    $_row[] = $tran->bank ? $tran->bank[0]['bank'] : '';
                    break;
                
                case 'FX Deal':
                    $_row[] = sprintf('%s %s/%s', $tran->rate['lock_rate'], $tran->rate['lock_currency_have'], $tran->rate['lock_currency_want']);
                    break;

                case 'Withdraw':
                    $_row[] = 'Withdraw to';
                    break;    
                default:
                    $_row[] = $tran->type;
                    break;
            }
            
            if ($type != "FX Deal")
            { 
                if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                {
                    $_row[] = sprintf('<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> %s </span>', overdueCheck($tran->created_at));
                }          
                else
                {
                    $_row[] = sprintf('<span class="label label-%s">%s</span>', transactionStatusStyle($tran->status), $tran->status);
                }
            }
            switch ($tran->type) 
            {
                case 'Deposit':
                    $_row[] = sprintf('<font class="green">+%s</font>', $tran->amountFormat);
                    break;

                case 'FX Deal':
                    $_row[] = sprintf('<font class="">%s</font>', $tran->amountFormat);
                    break;

                case 'Withdraw':
                    $_row[] = sprintf('<font class="red">-%s</font>', $tran->amountFormat);
                    break;
                
                default:
                    $_row[] = sprintf('<font class="green">%s</font>', $tran->amountFormat);
                    break;
            }

            $action_block = '';
            if (action_check($tran->id, $tran->receipt_id))
            {
                if (trans_perm($tran->type, 'clear', FALSE) OR trans_perm($tran->type, 'voided', FALSE) OR trans_perm($tran->type, 'cancel', FALSE))
                {
                    $action_block = '<div class="btn-group actions"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down f14p"></i></button><ul class="dropdown-menu">';
                    
                    if (trans_perm($tran->type, 'clear', FALSE))
                    {
                        $action_block .= '<li><a href="/admin/transaction/clear/' . $tran->id . '">Clear</a></li>';
                    }
                    if (trans_perm($tran->type, 'voided', FALSE))
                    {
                        $action_block .= '<li><a href="/admin/transaction/cancel/Voided/' . $tran->id . '">Void</a></li>';
                    }

                    if (trans_perm($tran->type, 'cancel', FALSE))
                    {
                        $action_block .= '<li><a href="/admin/transaction/cancel/Canceled/' . $tran->id . '">Cancel</a></li>';
                    }

                    $action_block .= '</ul></div>';
                }
            }
            if ($type != "FX Deal")
            {
                $_row[] = $action_block;
            }
            $_aa_data[] = $_row;
        }
                        
        $output = array(
            "aaData"            => $_aa_data,
            "total_count"       => $total_count,
        );

        return json_encode($output);
    }

    /**
     * 显示 Transaction 相关的receipt信息
     *
     * @param string $id
     * @return void
     */
    public function getReceipt($id = NULL)
    {
        // 权限检查
        check_perms('view_add_fund,view_withdraw,view_fx', 'OR');

        if ( ! $receipt = Receipt::find($id)) App::abort(404, 'Page not found'); 

        $view = 'admin.transaction.receipt';
        // 反序化收据详情
        $data = unserialize($receipt['data']);


        $receipt_arr = $receipt->toArray();
        $receipt_obj = $receipt;

        // 获取所属用户资料
        $user = User::find($receipt->uid);
        $type = $receipt->type;

        // 获取管理员notes
        $notes = $receipt->notes;

        // 相关transactions
        $transactions = $this->tran
            ->where('receipt_id', '=', $id)
            ->orderBy('created_at', 'desc')->get();  


        if ($type == 'Withdraw')
        {
            $this->layout->content = View::make($view)
                    ->with('data', $data)
                    ->with('receipt', $receipt_arr)
                    ->with('receipt_obj', $receipt_obj)
                    ->with('user', $user)
                    ->with('type', $type)
                    ->with('notes', $notes)
                    ->with('transactions', $transactions)
                    ->with('currency', $data[0]['currency']);
            return;
        
        }

        $data['receipt'] = $receipt_arr;

        $data['receipt_obj'] = $receipt_obj;

        // 获取所属用户资料
        $data['user'] = $user;

        $data['type'] = $type;

        // 获取管理员notes
        $data['notes'] = $notes;

        // 相关transactions
        $data['transactions'] = $transactions;  

        $this->layout->content = View::make($view, $data); 
    }

    /**
     * 显示 Transaction 详细信息
     *
     * @param string $id
     * @return void
     */
    public function getDetail($id = NULL)
    {
        if ( ! $detail = $this->tran->find($id)) return Redirect::back();

        // 权限检查
        trans_perm($detail->type);

        // 反序化transaction详情
        $data = unserialize($detail->data);

        $data['detail'] = $detail;
        $data['type'] = $detail->type;

        // 相关transactions
        $data['transactions'] = $this->tran
            ->where('receipt_id', '=', $detail->receipt_id)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')->get();

        // 相关Receipt
        $data['receipt'] = Receipt::where('id', '=', $detail->receipt_id)->first();

        // 相关的 activity
        $data['activities'] = Activity::where('obj_id', '=', $id)
             ->orderBy('created_at', 'desc')
             ->get(); 

        // 反序化收据详情
        $data['receipt_data'] = unserialize($data['receipt']->data);

        $this->layout->content = View::make('admin.transaction.detail', $data);
    }
    
    /**
     *  Transaction  clear 操作
     *
     * @param string $id
     * @return void
     */
    public function getClear($id = NULL)
    {
        if ( ! $detail = $this->tran->find($id)) return Redirect::back();

        // 权限检查
        trans_perm($detail->type, 'clear');

        if (in_array($detail->status, $this->final)) return Redirect::back()->with('error', 'This operation is invalid!');

        // 汇进／汇出的银行信息      
        if ($detail->type == 'Deposit' OR $detail->type == 'Withdraw') 
        {
            // 金额未分配完，不允许clear
            if ($detail->diff_amount > 0) 
            {
                return Redirect::back()->with('error', 'The bank infomation has not been completed. unhandled amount: ' . $detail->diff_amount);
            }
        }
        $result = $detail->clear();

        // 无法clear
        if ($result === FALSE) 
        {
            return Redirect::back()->with('error', 'The bank balance is not enough!');
        }

        $data = unserialize($detail->data);

        switch ($detail->type) 
        {
            case 'Deposit':
                $action = 'Cleared';
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['currency'], $data['amount'], TRUE));
                break;

            case 'FX Deal':
                $action = 'Cleared';
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['lock_currency_want'], $data['lock_amount'] * $data['lock_rate'], TRUE));
                break;

            case 'Withdraw':
                $action = 'Withdrawed';
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['currency'], $data['amount'], TRUE));            
                break;
            
            default:
                $action = 'Cleared';
                $desc = sprintf('Transaction ID %s', transaction_detail_url($detail->id));
                break;
        }
        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'customer_id'   => $detail->uid,
            'activity_type' => 'Transactions',
            'action'        => $action,
            'description' => $desc
        ));

        return Redirect::back()->with('action', TRUE);
    }

    /**
     *  Transaction cancel 操作
     *
     * @param string $action Canceled|Voided
     * @param string $id
     * @return void
     */
    public function getCancel($action = 'Canceled', $id = NULL)
    {
        if ( ! $detail = $this->tran->find($id)) return Redirect::back();

        // 权限检查
        if ($action == 'Voided') 
        {
            trans_perm($detail->type, 'voided');
        }
        else
        {
            trans_perm($detail->type, 'cancel');
        }       

        if (in_array($detail->status, $this->final)) return Redirect::back()->with('error', 'This operation is invalid!');

        $detail->cancel($action);

        $data = unserialize($detail->data);
        switch ($detail->type) 
        {
            case 'Deposit':
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['currency'], $data['amount'], TRUE));
                break;

            case 'FX Deal':
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['lock_currency_want'], $data['lock_amount'] * $data['lock_rate'], TRUE));
                break;

            case 'Withdraw':
                $desc = sprintf('Transaction ID %s %s', transaction_detail_url($detail->id), currencyFormat($data['currency'], $data['amount'], TRUE));            
                break;
            
            default:
                $desc = sprintf('Transaction ID %s', transaction_detail_url($detail->id));
                break;
        }
        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'customer_id'   => $detail->uid,
            'activity_type' => 'Transactions',
            'action'        => $action,
            'description' => $desc
        ));

        return Redirect::back()->with('action', TRUE);
    }

    /**
     *  Transaction 提醒功能
     *
     * @param string $action sms_reminder|email_reminder
     * @param string $id
     * @return void
     */
    public function getReminder($action = 'email_reminder', $id = NULL)
    {
        // 权限检查
        check_perm($action);

        if ( ! $detail = $this->tran->find($id))
        {
            $this->push('error', array('msg' => "The transaction is not exists!"));
        }

        $data = array(
            'user' => $detail->user->full_name,
            'email' => $detail->user->email            
        );
        
        $email = $data['email'];

        Mail::queue('emails.deal_overdue', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Overdue Submit Deal');
        }); 

        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'customer_id'   => $detail->uid,
            'activity_type' => 'Transactions',
            'action'        => 'Reminder',
            'description' => 'Send email to reminder overdue deal ' . transaction_detail_url($detail->id)
        ));  

        return $this->push('ok');  
    }

    /**
     *  获取可选的银行
     *
     * @param string transaction_id
     * @return void
     */
    public function getBankSelecter($id = NULL)
    {
        if ( ! $detail = $this->tran->find($id))
        {
            return $this->push('error');
        }

        // 权限检查
        trans_perm($detail->type, 'edit_bank');

        $bank = new Bank;
        /**
         * 取款银行的分配不受银行状态的限制。即，
         * 无论银行状态是on/off, 都可以从该银行账号取款
         */
        $bank_status = $detail->type == 'Deposit' ? 1 : 0;

        // 已经是汇款银行的不可选
        $except = array();
        if ($detail->bank) 
        {
            foreach ($detail->bank as $b) 
            {
                $except[] = $b->id;
            }
        }

        // 获取可选银行
        $banks = $bank->getBankSelect($detail->currency, $bank_status, $except);
        $bank_result = array();

        /**
         * 如果 type = deposit, 则按照 balance 升序排列
         * 如果 type = withdraw, 则按照 balance 降序排列
         */
        $bank_arr_by_id = array();
        $bank_arr_balance = array();
        foreach ($banks as $b)
        {
            $bank_arr_by_id[$b->id] = $b;
            $bank_arr_balance[$b->id] = $b->getAmount($detail->currency);
        }

        $detail->type == 'Deposit' ? asort($bank_arr_balance, SORT_NUMERIC) :
                                    arsort($bank_arr_balance, SORT_NUMERIC);

        $idx = 0;
        foreach ($bank_arr_balance as $key => $balance) 
        {
            $b = $bank_arr_by_id[$key];

            $bank_result[] = 
                array(
                    'order' => $idx++,
                    'value' => $b->id, 
                    'text' => sprintf('%s %s %s %s %s', 
                                        $b->bank, 
                                        $b->branch ? ' (' . $b->branch . ')' : '', 
                                        $b->account_number ? ' - No. ' . $b->account_number : '',
                                        ' - Balance ' . number_format($b->getAmount($detail->currency), 2),
                                        $b->limit == 0 ? ' - Unlimited' :' - Limit ' . number_format($b->limit))
                    );
        }

        return $this->push('ok', array('data' => $bank_result));  
 
    }


    /**
     *  提交选择的银行
     *
     * @param string transaction_id
     * @return void
     */
    public function postBankAdd($id = NULL)
    {
        if ( ! $detail = $this->tran->find($id))
        {
            return $this->push('error', array('msg' => "The transaction is not exists!"));
        }

        // 权限检查
        trans_perm($detail->type, 'edit_bank');
     
        $currency = '';
        $validate_arr = array(
            'bank_id'         => 'required|exists:banks,id',
            'amount'          => 'required|numeric|max:' . $detail->diff_amount,
        );   

        $currency = $detail->currency;

        $validator = Validator::make(Input::all(), $validate_arr); 
        if ($validator->fails())
        {
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }

        $bank_id = Input::get('bank_id');
        $amount = Input::get('amount');

         // 创建银行信息
        $bankinfo = Bank::find($bank_id);
        if (stristr($bankinfo->currency, $currency)) 
        {
            // 超出银行限额
            if ( ! $this->limitCheck($bankinfo, $detail, $amount)) 
            {
                return $this->push('error', array('msg' => "The bank limit is not enough."));
            }
            // withdraw时银行余额不足
            if ($detail->type == 'Withdraw' AND $bankinfo->getAmount($currency) < $amount) 
            {
                return $this->push('error', array('msg' => "The bank balance is not enough!"));
            }
            $banklog = TransactionBank::create(array(
                'bank_id'           => $bankinfo->id,
                'transaction_id'    => $detail->id,
                'currency'          => $currency,
                'amount'            => $amount
            ));

            $data = array(
                'item_id'           => $banklog->id,
                'account_number'    => $bankinfo->account_number,
                'bank_name'         => sprintf('%s %s', $bankinfo->bank, $bankinfo->branch ? '(' . $bankinfo->branch .')': ''),
                'balance'           => number_format($bankinfo->getAmount($currency), 2),
                'amount'            => $amount,
                'currency'          => $currency,
                'max_amount'        => $detail->diff_amount,
                'actions'           => trans_perm($detail->type, 'edit_bank', FALSE)
            );

            // add log
            Activity::log(array(
                'obj_id'   => $detail->id,
                'customer_id'   => $detail->uid,
                'activity_type' => 'Transactions',
                'action'        => 'Add Bank',
                'description' => 'Add bank to handle ' . transaction_detail_url($detail->id)
            ));

            return $this->push('ok', array('info' => $data));
        }
        return $this->push('error', array('msg' => 'Operation in error, please contact the administrator'));  
    }

    /**
     *  修改银行处理金额
     *
     * @param string transaction_id
     * @return void
     */
    public function postBankEdit($id = NULL)
    {
        if ( ! $detail = $this->tran->find($id))
        {
            return $this->push('error', array('msg' => "The transaction is not exists!"));
        }

        // 权限检查
        trans_perm($detail->type, 'edit_bank');

        $validator = Validator::make(Input::all(), array(
            'item_id'         => 'required|exists:transaction_bank,id',
            'amount'          => 'required|numeric'
        ));
        
        if ($validator->fails())
        {
            return $this->push('error', array('msg' => $validator->messages()->first()));
        }

        $item_id = Input::get('item_id');
        $amount = Input::get('amount');
        $item = TransactionBank::find($item_id);
        // 可修改的最大值

        //$max_amount = $detail->diff_amount + $item->amount;
        //if ($amount > $max_amount) 
        //{
        //    return $this->push('error', array('msg' => 'The amount may not be greater than ' . $max_amount));
        //}

         // 修改银行处理金额
        $bankinfo = Bank::find($item->bank_id);

        // 超出银行限额
        if ( ! $this->limitCheck($bankinfo, $detail, $amount)) 
        {
            return $this->push('error', array('msg' => "The bank limit is not support!"));
        }

        // withdraw时银行余额不足
        if ($detail->type == 'Withdraw' AND $bankinfo->getAmount($item->currency) < $amount) 
        {
            return $this->push('error', array('msg' => "The bank balance is not enough!"));
        }
        
        $item->amount = $amount;
        $item->save();

        $data = array(
            'amount'            => $amount,
            'max_amount'        => $detail->diff_amount
        );

        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'customer_id'   => $detail->uid,
            'activity_type' => 'Transactions',
            'action'        => 'Edit Bank',
            'description' => 'Edit bankinfo to handle ' . transaction_detail_url($detail->id)
        ));

        return $this->push('ok', array('info' => $data));
    }

    /**
     *  删除相关联的银行账号
     *
     * @param string transaction_id
     * @param int item_id
     * @return void
     */
    public function getBankRemove($id = NULL, $item_id = NULL)
    {
        if ( ! $detail = $this->tran->find($id))
        {
            return $this->push('error', array('msg' => "The transaction is not exists!"));
        }

        if ( ! $item = TransactionBank::where('transaction_id', $id)->where('id', $item_id)->first())
        {
            return $this->push('error', array('msg' => "The item is not exists!"));
        }

        // 权限检查
        trans_perm($detail->type, 'remove_bank');

        // add log
        Activity::log(array(
            'obj_id'   => $detail->id,
            'customer_id'   => $detail->uid,
            'activity_type' => 'Transactions',
            'action'        => 'Remove Bank',
            'description' => 'Remove bankinfo to handle ' . transaction_detail_url($detail->id)
        ));

        $item->delete();

        return $this->push('ok', array('info' => array('max_amount' => $detail->diff_amount)));
    }

    /**
     *  银行账号限额查询
     *
     * @param object bank
     * @param object transaction
     * @param float  amount
     * @return bool
     */
    public function limitCheck(&$bank, &$tran, $amount)
    {
        // 目前只有CNY需要判断限额
        if ($tran->currency == 'CNY') 
        {
            $check = TRUE;
            switch ($tran->type) 
            {
                case 'Deposit':
                    if ($bank->limit AND $bank->limit < $amount) 
                    {
                        $check = FALSE;
                    }
                    break;

                case 'Withdraw':  // 暂时withdraw与add fund用同一限额
                    if ($bank->limit AND $bank->limit < $amount) 
                    {
                        $check = FALSE;
                    }
                    break;
                
                default:
                    break;
            }
            return $check;
        }
        return TRUE;       
    }
}
