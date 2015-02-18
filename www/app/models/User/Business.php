<?php namespace WeXchange\Model;


class Business extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'business';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'uid';


    /**
     * 返回business address
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return sprintf('%s %s %s %s %s %s', $this->unit_number, $this->street_number, $this->street, $this->city, $this->state, $this->postcode);
    }
}