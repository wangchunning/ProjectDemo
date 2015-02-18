<?php namespace Controllers\Admin;

use DB;
use View;
use Redirect;
use Input;
use Request;
use Auth;
use User;
use App;

use Session;
use Carbon;

use Controllers\AdminController;

use AdminActivityLog;

/**

 */
class ReportController extends AdminController {


	public function getIndex()
	{
		
		return Redirect::to('/admin/report/activity-log');
	}
	
    public function getActivityLog()
    { 
        check_perm('view_activity_log');
        
        $_logs	= new AdminActivityLog;
        
        $_start_date	= '';
        $_end_date		= '';
        $_search		= '';
        
        if (Input::has('start_date'))
        {
        	$_start_date = Input::get('start_date');
        	$_logs->where('created_at', '>=', $_start_date);
        }
        if (Input::has('end_date'))
        {
        	$_end_date = Input::get('end_date');
        	$_logs->where('created_at', '<=', $_end_date);
        }
        if (Input::has('search'))
        {
        	$_search = Input::get('search');
        	$_logs->whereRaw("user_name like ", array($_search."%"));
        }

        $_logs = $_logs->paginate($this->DEFAULT_PAGINATION_CNT);
        
        $this->data['start_date'] 	= $_start_date;
        $this->data['end_date'] 	= $_end_date;
        $this->data['search'] 		= $_search;
        $this->data['logs'] 		= $_logs;
        
        $this->layout->content = View::make('admin.report.activity_log.index', $this->data);
    }



}
