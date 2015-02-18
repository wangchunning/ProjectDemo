<?php namespace WeXchange\Model;
 

class RecipientBank extends \Eloquent {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipient_banks';

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
        'recipient_id', 
        'swift_code',
        'country',
        'bank_name',
        'branch_name',
        'unit_number',
        'street_number',
        'street',
        'city',
        'state',
        'postcode',
        'account_bsb',
        'account_number',
        'currency'
    );

    public function recipient()
    {
        return $this->belongsTo('Recipient', 'recipient_id');
    }

}