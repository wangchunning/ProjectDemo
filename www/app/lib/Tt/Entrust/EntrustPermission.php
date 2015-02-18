<?php namespace Tt\Entrust;

class EntrustPermission extends \Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Many-to-Many relations with Roles
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'permission_role');
    }

    /**
     * Before delete all constrained foreign relations
     *
     * @param bool $forced
     * @return bool
     */
    public function beforeDelete( $forced = false )
    {
        try {
            \DB::table('permission_role')->where('permission_id', $this->id)->delete();
        } catch(Execption $e) {}

        return true;
    }

}
