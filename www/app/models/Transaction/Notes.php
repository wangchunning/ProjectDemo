<?php namespace WeXchange\Model;

class Notes extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction_notes';

    /**
     * The property specifies which attributes should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = array('item_id', 'uid', 'operator_id', 'type', 'desc');

    /**
     * Get the operator that the note belongs to.
     *
     * @return object
     */
    public function operator()
    {
        return $this->belongsTo('User', 'operator_id');
    }
}