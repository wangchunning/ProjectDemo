<?php namespace WeXchange\Model;


class TransferHistory extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transfer_history';

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
        'recipient_id',
        'receipt_id',
        'currency',
        'amount',
        'aud_rate',
        'amount_to_aud'
    );  

    /**
     * 返回 receipt 模型
     *
     * @return WeXchange\Model\Receipt
     */
    public function receipt()
    {
        return $this->belongsTo('Receipt');
    }      
}