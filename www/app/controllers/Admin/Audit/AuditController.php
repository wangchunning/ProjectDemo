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

/**

 */
class AuditController extends AdminController {


    public function getIndex()
    { 
        check_perm('view_audit');
        
        
        $this->layout->content = View::make('admin.audit.index', $this->data);
    }



}
