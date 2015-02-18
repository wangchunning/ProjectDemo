<?php namespace Tt\Model;

use Session;

class LicaiProduct extends \Eloquent {

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
    protected $table = 'licai_product';

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
        'status',
    );

    /**
     * 临时保存收据信息的会话名称
     *
     * @var string
     */
    //protected static $sessionName = 'receiptSession';

}