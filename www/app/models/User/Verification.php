<?php namespace Tt\Model;
 

class Verification extends \Eloquent {

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
    protected $table = 'user_verification';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * 返回关联用户模型
     *
     * @return WeXchange\Model\User
     */
    public function user()
    {
        return $this->belongsTo('User', 'uid');
    }

}