@section('title')
提现详情
@stop

@section('content')
@include('admin.finance.subnav')

<div class="container">       
    <div class="white-box-body all-radius">

        @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
        @endif 

        <h4 class="mt0">提现详情</h4>

        <div class="row" >
            <div class="col-sm-9 pull-left">
                <div class="flat-panel receipt-item-panel mt20">   
                    
                    <dl class="dl-horizontal">
                    	<dt>流水号 </dt>
                    	<dd>{{ $user_tx->receipt_number }}</dd>
                        <dt>提现金额 </dt>
                    	<dd class="blue">{{ currencyFormat($user_tx->available_amount) }}</dd>                    	
                    	<dt>收款人姓名</dt>
                    	<dd class="blue">{{ $user_tx->account_name }}</dd>
                    	<dt>银行卡号</dt>
                    	<dd class="blue">{{ format_account_number($user_tx->account_number) }}</dd>
                        <dt>银行名称 </dt>
                        <dd>{{ sprintf('%s %s', $user_tx->bank_name, $user_tx->bank_full_name) }}</dd>

                    	<dt>提现费用</dt>
                    	<dd>{{ currencyFormat($user_tx->fee_amount) }}</dd>
                    	<dt>金额总计</dt>
                    	<dd>{{ currencyFormat($user_tx->total_amount) }}</dd>
                    	<dt>提交时间</dt>
                    	<dd>{{ pretty_date($user_tx->created_at) }}</dd>
                    </dl> 
                    
                    <hr class="mt30">
					@include('admin.customer.include_tx_detail_customer_info')					
				</div> 
			</div>
   
			<div class="col-sm-3">
				<div id="command-affix">				
					<div class="tx-detail-status label-{{ WithdrawTx::status_style($user_tx->status) }}">
						<p class="f20p">状态</p>
						<p> {{WithdrawTx::status_desc($user_tx->status)}} </p>
					</div>
					@if ($user_tx->status == WithdrawTx::STATUS_PENDING)
					<h5>操作</h5>
					{{ Form::open(array('url' => url('/admin/finance/withdraw-clear', $user_tx->id), 'role' => 'form', 'id' => 'clearFrm')) }}
					<p><button type="submit" class="tx-action-btn button button-rounded button-flat-primary clear-btn">确定出账</button></p>
					{{ Form::close() }}
					{{ Form::open(array('url' => url('/admin/finance/withdraw-cancel', $user_tx->id), 'role' => 'form', 'id' => 'cancelFrm')) }}
					<p><button type="submit" class="tx-action-btn button button-rounded button-flat cancel-btn">驳回请求</button></p>
					{{ Form::close() }}
					@endif
				</div>

            </div>
        </div>


    </div>
</div>

@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function() {
	$('#command-affix').affix({
		offset: {
			  top: 200, 
			  bottom: function () {
			        return (this.bottom = $('.footer').outerHeight(true))
			      }
			  }
	});
	
	//init_confirm_modal(btn_class, title, submit_type, frm) 
	
	init_confirm_modal('clear-btn', '确定要出账么？', 'submit', '#clearFrm');
	init_confirm_modal('cancel-btn', '确定要驳回么？', 'submit', '#cancelFrm');
	
});
</script>
@stop
