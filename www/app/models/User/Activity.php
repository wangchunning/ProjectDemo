<?php namespace WeXchange\Model;

use Auth;
use Request;

class Activity  extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * Get the user that the activity belongs to.
     *
     * @return object
     */
    public function user()
    {
            return $this->belongsTo('User', 'uid');
    }

    /**
     * Create an activity log entry.
     *
     * @param mixed
     * @return boolean
     */
    public static function log($data = array())
    {
        if (is_object($data)) $data = (array) $data;
        if (is_string($data)) $data = array('activity' => $data);

        $user = is_login('admin') ? Auth::admin()->user() : Auth::member()->user();

        if ( ! $user->uid)  return;

        $activity = new static;
        $activity->uid = $user->uid;
        $activity->user_type = $user->type;
        $activity->obj_id = isset($data['obj_id']) ? $data['obj_id'] : "";
        $activity->customer_id = isset($data['customer_id']) ? $data['customer_id'] : "";
        $activity->activity_type = isset($data['activity_type']) ? $data['activity_type'] : "";
        $activity->description = isset($data['description']) ? $data['description'] : "";
        $activity->action = isset($data['action']) ? $data['action'] : "";

        //set action and allow "updated" boolean to replace activity text "Added" or "Created" with "Updated"
        if (isset($data['updated'])) {
            if ($data['updated']) {
                $activity->description = str_replace('Added', 'Updated', str_replace('Created', 'Updated', $activity->description));
                $activity->action = "Updated";
            } else {
                $activity->action = "Created";
            }
        }
        if (isset($data['deleted']) && $data['deleted'])
                $activity->action = "Deleted";

        $activity->ip_address = Request::getClientIp();
        $activity->save();
        return true;
    }
}