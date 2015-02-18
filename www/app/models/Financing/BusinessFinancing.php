<?php namespace Tt\Model;


class BusinessFinancing extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'business_financing';

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
    /*
    protected $fillable = array(    

    );
    */
    const STATUS_CREATED = 0;
    const STATUS_CHECKED = 10;
    const STATUS_PROJECTED = 20;
    const STATUS_ONLINE = 30;
    const STATUS_DEALING = 40;
    const STATUS_DELT = 50;

    const STATUS_CANCELLED = 1001;
    const STATUS_VOIDED = 1002;

    public function getBusinessNameAttribute()
    {
        $_user = User::find($this->apply_uid);
        $_user_business = $_user->business();
        
        return isset($_user_business->business_name) ? 
                        $_user_business->business_name : '';
    }

    public function applications()
    {
        return InvestBusinessFinancing::where('business_financing_id', $this->id)->get();
    }

    /**
     * 临时保存收据信息的会话名称
     *
     * @var string
     */
    //protected static $sessionName = 'receiptSession';
    public function getStatusDescAttribute()
    {
        $_desc = ''; 
        $_status = $this->status;
        switch ($_status)
        {
            case BusinessFinancing::STATUS_CREATED:
                $_desc = '待初审';
                break;
            case BusinessFinancing::STATUS_CHECKED:
                 $_desc = '待立项';
                break;
            case BusinessFinancing::STATUS_PROJECTED:
                 $_desc = '待披露';
                break;
            case BusinessFinancing::STATUS_ONLINE:
                 $_desc = '已披露';
                break;
            case BusinessFinancing::STATUS_DEALING:
                 $_desc = '交易中';
                break;
             case BusinessFinancing::STATUS_DELT:
                 $_desc = '已成交';
                break;                               
            case BusinessFinancing::STATUS_CANCELLED:
                 $_desc = '已驳回';
                break;
            case BusinessFinancing::STATUS_VOIDED:
                 $_desc = '已下线';
                break;
            default:
                $_desc = '待初审';
                break;

        }

        return $_desc;
    }
}