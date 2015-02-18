<?php namespace Controllers\Finance;

use View;
use Auth;
use Carbon;
use App;
use Controllers\AdminController;
use Breadcrumb;
use Input;
use Validator;
use Redirect;

use User;

/**
 *  
 *
 */
class FinanceController extends AdminController {

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
                // set breadcrumb
        Breadcrumb::map(array('首页' => url('/')))->append('我的企业融资');

        $_user = real_user();

        $this->data['business_finance'] = $_user->business_financing();
        $this->layout->content = View::make('financing.index', $this->data);       
    }  

    /**
     * 
     *
     * @return void
     */
    public function getApply()
    {                                                                     
        // set breadcrumb
        Breadcrumb::map(array(
                '首页' => url('/'),
                '我的企业融资' => url('/financing')
            ))->append('企业融资申请');

        $_user = real_user();
        if ($_user->type == USER::TYPE_VALUE_NORMAL)
        {
            return Redirect::to('/profile/update-business-info')
                    ->with('error', '请您升级为企业账户才能进行债务转让操作');
        }

        $this->data['user'] = $_user;
        $this->layout->content = View::make('financing.apply', $this->data);
    }  

    /**
     *
     * @return Redirect
     */
    public function postApply()
    {          
        $messages = array(

        );

        $validator = Validator::make(Input::all(), array(

        ), $messages);

        if ($validator->fails())
        {
            return Redirect::to('/financing/apply')->withInput()->withErrors($validator);
        }  

        $_user = real_user();

        $_asset_obj = new BusinessFinance;
        $_asset_obj->license_file = Input::get('license_file');
        $_asset_obj->business_register_file = Input::get('business_register_file');
        $_asset_obj->tax_register_file = Input::get('tax_register_file');
        $_asset_obj->years_finance_report_file = Input::get('years_finance_report_file');
        $_asset_obj->recent_finance_report_file = Input::get('recent_finance_report_file');
        $_asset_obj->bank_account = Input::get('bank_account');
        $_asset_obj->expect_loan_amount = Input::get('expect_loan_amount');
        $_asset_obj->loan_during = Input::get('loan_during');
        $_asset_obj->pledge = Input::get('pledge');
        $_asset_obj->guarantee = Input::get('guarantee');
        $_asset_obj->financing_purpose = Input::get('financing_purpose');

        $_asset_obj->apply_uid = $_user->uid;
        $_asset_obj->status = BusinessFinance::STATUS_PENDING;

        $_asset_obj->save();


        return Redirect::to('/financing/apply');      
    }

    /**
     */
    public function getBusinessFinancingDetail($id = NULL)
    {
        // set breadcrumb
        Breadcrumb::map(array(
                '首页' => url('/'),
                '我的企业融资' => url('/financing')
            ))->append('企业融资详细信息');

        if (!$_obj = BusinessFinance::find($id)) 
        {
            return Redirect::back();
        }

        // 权限检查
        //trans_perm($detail->type);

        $data['detail_obj'] = $_obj;

        $this->layout->content = View::make('financing.business_financing_detail', $data);
    }
}