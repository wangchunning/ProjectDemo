<?php namespace WeXchange\Model;

use Carbon;
class BillingDetail extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'billing_detail';

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
    protected $fillable = array('bill_id', 'currency_from', 'currency_to', 'rate', 'bank_buy_amount', 'bank_sell_amount');

    /**
     * 定义 query scope：通过时间筛选
     *
     * @param  Eloquent
     * @param  string
     * @param  string
     * @param  string
     * @return WeXchange\Model\BillingDetail
     */
    public function scopeTimearea($query, $dw, $start_date, $expriy_date)
    {
        if ( ! $start_date AND ! $expriy_date AND $dw)
        {
            return $query->where('billing_detail.created_at', '>', word_to_date($dw));                   
        }

        if (urldecode($start_date))
        {
            $query = $query->where('billing_detail.created_at', '>=', time_to_search($start_date));
        }

        if (urldecode($expriy_date))
        {
            $query = $query->where('billing_detail.created_at', '<=', time_to_search($expriy_date));
        }

        return $query;
    }  

    /**
     * 当前billing detail是否有设置basic rate，即含有USDCNY或CNYUSD结算
     *
     * @return bool
     */
    public function has_basic()
    {
        $has_basic = false;

        // 含有USDCNY或CNYUSD结算
        if (($this->currency_from == 'CNY' && $this->currency_to == 'USD') || 
                ($this->currency_from == 'USD' && $this->currency_to == 'CNY')) 
        {
            $has_basic = true;
        }

        return $has_basic;  
    } 

}