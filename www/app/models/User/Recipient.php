<?php namespace WeXchange\Model;
 
 use Session;
 use Input;
 use Auth;
 use TransferHistory;
 use DB;

class Recipient extends \Eloquent {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients';

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
    protected $fillable = array(
        'uid', 
        'email',
        'transfer_country', 
        'account_name',
        'address',
        'address_2',
        'address_3',
        'postcode',
        'phone_code',
        'phone_type',
        'phone',
    );

    /**
     * 临时保存的收款人信息字段
     *
     * @var array
     */
    protected static $sessionStoreKeys = array(
            'transfer_country',
            'swift_code',
            'country',
            'bank_name',
            'branch_name',
            'bank_unit_number',
            'bank_street_number',
            'bank_street',
            'bank_city',
            'bank_state',
            'bank_postcode',
            'account_bsb',
            'account_number',
            'account_name',
            'address',
            'address_2',
            'address_3',
            'postcode',
            'country',
            'phone_type',
            'phone_code',
            'phone',
            'currency',
            'save_to_book'
        );

    /**
     * 提取并返回收款人
     *
     * @return array
     */
    public static function processing($input)
    {
        $output = array();

        // 汇款到 balance？
        //if (Input::get('transfer_to') == 'balance')
        if ($input['transfer_to'] == 'balance')
        {
            return $output;
        }

        $user = real_user();
        // 银行账号为地址簿里的?
        //if (Input::get('transfer_to') == 'address')
        if ($input['transfer_to'] == 'address')
        {
        	//$account_ids = Input::get('account_id');
        	$account_id = $input['account_id'];
            return (array)$user->recipientBankAccounts()->where('recipient_banks.id', $account_id)->first();
            //return (Array)$user->recipientBankAccounts()->whereIn('recipient_banks.id', $account_ids)->get();
        }

        // 收款人从地址簿里选择？
        //if (Input::get('transfer_to') == 'new' AND $recipient_id = Input::get('recipient_id')) 
        if ($input['transfer_to'] == 'new' AND $recipient_id = $input['recipient_id']) 
        {
            $recipient = $user->recipients()->where('id', $recipient_id)->first();
            if ($recipient) 
            {
                $input = array_merge($input, $recipient->toArray());
            }  
        }

        // 过滤 keys
        $input = array_only($input, self::$sessionStoreKeys);

        // 根据 phone code 确定收款人国家
        $countries = countries();
        //$input['transfer_country'] = array_search(Input::get('phone_code'), $countries);
        $input['transfer_country'] = array_search($input['phone_code'], $countries);
        foreach ($input as $key => $val)
        {
            $output[$key] = $val;
        }        

        return $output;
    }    

    /**
     *  收款人的流水单
     *
     * @return WeXchange\Model\Transaction
     */
    public function transactions()
    {

        $user = real_user();
        $account_numbers = array();
        /**
         * account numbers
         * select account_number from recipient_banks where recipient_id = XXX
         */
        $recipient_banks = RecipientBank::where('recipient_id', '=', $this->id)
                                ->select('account_number')
                                ->get()
                                ->toArray();
        foreach ($recipient_banks as $recipient_bank)
        {
            $account_numbers[] = $recipient_bank['account_number'];
        }

        if (empty($account_numbers))
        {
            return array();
        }

        return Transaction::whereIn('account_number', $account_numbers)
        ->where('uid', $user->uid)
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    }

    /**
     *  last transfer
     *
     * @return WeXchange\Model\TransferHistory
     */
    public function lastTransfer()
    {
        $user = real_user();

        return TransferHistory::where('uid', '=', $user->uid)
                    ->where('recipient_id', '=', $this->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    /**
     *  Transfer History
     *
     * @return WeXchange\Model\TransferHistory
     */
    public function TransferHistory()
    {
        $user = real_user();
        return TransferHistory::where('uid', '=', $user->uid)
                    ->where('recipient_id', '=', $this->id)
                    ->get();
    }

    /**
     *  Total transfer to AUD
     *
     * @return float
     */
    public function totalTransfer()
    {
        $user = real_user();

        $rs = TransferHistory::select(DB::raw('SUM(amount_to_aud) as total'))
        ->where('uid', '=', $user->uid)
        ->where('recipient_id', '=', $this->id)
        ->first()->toArray();
        
        return $rs['total'];
    }

    /**
     * 电话号码访问器
     *
     * @return string
     */
    public function getPhoneNumberAttribute()
    {
        return sprintf('+%s %s', $this->phone_code, $this->phone);
    }

    /**
     * 收款人银行信息
     *
     * @return WeXchange\Model\RecipientBank
     */
    public function banks()
    {
        return $this->hasMany('RecipientBank', 'recipient_id');
    }
}