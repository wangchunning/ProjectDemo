<?php namespace Tt\Model;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

	protected $table = 'roles';
    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

}