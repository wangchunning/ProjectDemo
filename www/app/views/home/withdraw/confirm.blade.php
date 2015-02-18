@section('title')
确认提现信息 - 天添财富
@stop

@section('content')
@include('home.subnav')
@include('breadcrumb')

{{ Form::open(array('url' => 'withdraw/confirm', 'role' => 'form', 'id' => 'frm')) }}
<div class="container">
    <div class="dashboard-body">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif           

        <div class="flat-panel receipt-item-panel mt20">   
            
            <h4>确认提现信息</h4>

            <p class="help-block">
               	 支付前余额 {{ currencyFormat($balance->available_amount) }}    
            </p>

            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">

                    <dl class="dl-horizontal">
                        <dt>提现金额 </dt>
                    	<dd>{{ currencyFormat($tx->available_amount) }}</dd>                    	
                    	<dt>收款人姓名</dt>
                    	<dd>{{ sprintf('%s (******** %s)', $profile->real_name, substr($profile->id_number, -4)) }}</dd>
                    	<dt>银行卡号</dt>
                    	<dd>{{ $tx->account_number }}</dd>
                        <dt>银行名称 </dt>
                        <dd>{{ sprintf('%s %s', $tx->bank_name, $tx->bank_full_name) }}</dd>

                    	<dt>提现费用</dt>
                    	<dd>{{ currencyFormat($tx->fee_amount) }}</dd>
                    	<dt>金额总计</dt>
                    	<dd>{{ currencyFormat($tx->total_amount) }}</dd>
                    	
						<div class="form-group">
                    	<dt class="mt5"><label for="password">交易密码</label></dt>
                    	<dd class="mt10">
                    		<div class="col-sm-5 pl0">
                    			<input type="password" class="form-control " name="password"
									data-bv-notempty="true"
		                			data-bv-notempty-message="* 请输入交易密码">
		                	</div>
		                </dd>
		                </div>
                    </dl> 
                </div>

            </div>			
     
        </div>
	
		<hr class="mb20">
		
        <div class="ml20 mb20">
            <button type="submit" class="button button-rounded button-flat-primary">确认提现</button>                                       
        </div>

    </div>
</div>
{{ Form::close() }}
@stop

@section('inline-js')

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });

});
</script>
@stop
