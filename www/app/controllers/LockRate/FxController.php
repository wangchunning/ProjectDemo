<?php namespace Controllers\LockRate;

use View;
use Auth;
use Input;
use Validator;
use Redirect;
use Bank;
use Session;
use Receipt;
use Exchange;
use Balance;
use Controllers\AuthController;

/**
 *  FX Controller
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class FxController extends AuthController {

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'layouts.dashboard';

    /**
     *  List for FX
     *
     * @return void
     */
    public function getIndex()
    {
        $user = real_user();
        // FX transactions
        $this->data['fxs'] = $user
        ->transactions()
        ->orderBy('created_at', 'desc')
        ->where('type', '=', 'FX Wish')
        ->orWhere('type', '=', 'FX Deal')
        ->paginate(30);        

        $this->layout->content = View::make('lockrate.fx.index', $this->data);
    }  

    /**
     * Lock the rate
     *
     * @return Redirect
     */
    public function postIndex()
    {
        // Save the amount
        Exchange::amount(Input::get('amount_have'));

        return Redirect::to('fx/confirm');
    }         

    /**
     * Show the confirm fx detail
     *
     * @return void
     */
    public function getConfirm()
    {
        // have not lock the rate?
        if ( ! Exchange::check())
        {
            return Redirect::to('lockrate');
        }

        // Available balance
        $this->data['balance'] = availableBalance(Exchange::read('lock_currency_have'));

        $this->layout->content = View::make('lockrate.fx.confirm', $this->data);
    }

    /** 
     * Post the fx request
     *
     * @return Redirect
     */
    public function postConfirm()
    {
        $messages = array(
            'payment_method.balance_amount' => '账户余额不足'
        );

        $validator = Validator::make(Input::all(), array(
            'payment_method' => 'required|in:balance|balance_amount',
        ), $messages);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Combine fx info
        $data['fx'] = Exchange::lockToArray();

        $user = real_user();
        // Create a receipt
        $receipt = Receipt::create(array(
            'id'     => rn('F'),
            /**
             * uid      - receipt 所有者的 uid
             * opt_uid  - 操作人员 uid
             *
             * 如果一个 business member 在管理 business 账户，则
             *     uid      = business uid
             *     opt_uid  = business member uid
             *
             * 如果登录用户管理的是自己的账户，则 uid = opt_uid
             * 
             */
            'uid'    => $user->uid,
            'opt_uid'=> Auth::member()->user()->uid,
            'type'   => 'FX Deal',
            'status' => 'Waiting for fund',
            'data'   => serialize($data)
        ));

        // Store receipt id in the session only for the next request
        Session::flash('receipt_id', $receipt->id);

        return Redirect::to('fx/receipt');        
    }

    /**
     * Show fx receipt
     *
     * @return void
     */
    public function getReceipt()
    {
        // Get the receipt id in sesstion
        if ( ! $receipt_id = Session::get('receipt_id'))
        {
            return Redirect::to('fx');            
        }

        // Get the receipt
        if ( ! $this->data['receipt'] = Receipt::where('id', '=', $receipt_id)->get()->first())
        {
            return Redirect::to('fx');
        }

        $this->layout->content = View::make('lockrate.fx.receipt', $this->data);   
    }
}