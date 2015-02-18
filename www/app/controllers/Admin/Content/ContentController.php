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
class ContentController extends AdminController {


    public function getIndex()
    { 
        check_perm('view_content');
        
        
        $this->layout->content = View::make('admin.content.index', $this->data);
    }



}
