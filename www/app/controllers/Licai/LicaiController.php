<?php namespace Controllers\Licai;

use View;
use Auth;
use Carbon;
use App;
use Controllers\BaseController;
use Controllers\AuthController;
use Breadcrumb;
use Input;
use Validator;
use Redirect;

use User;

use LicaiProduct;

/**
 *  
 *
 */
class LicaiController extends BaseController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.basic';

    /**
     * 
     *
     * @return void
     */
    public function getIndex()
    {
        Breadcrumb::map(array('首页' => url('/')))->append('理财产品');

        $this->data['licai'] = LicaiProduct::take(10)->get();

        $this->layout->content = View::make('licai.index', $this->data);
    }  

}