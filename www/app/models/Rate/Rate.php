<?php namespace WeXchange\Model;

class Rate extends \Eloquent {

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
    protected $table = 'rates';

    /**
     * 获取参与兑换的货币
     *
     * @return array
     */
    public function getCurrencies()
    {
        $output = array();

        foreach($this->all() as $rate)
        {
            $output[] = $rate->from;
            $output[] = $rate->to;
        }

        return array_unique($output);
    }

    /**
     * 获取 Local Currency 可兑换的货币
     *
     * @param  string $currency
     * @return array
     */
    public function getForeignCurrencies($currency = '')
    {
        $output = array();

        $rs = $this->where('from', '=', $currency)
            ->orWhere('to', '=', $currency)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($rs as $rate)
        {
            $output[] = $rate->from == $currency ? $rate->to : $rate->from;
        }
        
        return array_unique($output);
    }
}