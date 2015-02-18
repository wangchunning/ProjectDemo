@section('title')
充值 - 天添财富
@stop

@section('content')
@include('home.subnav')
@include('breadcrumb')

<div class="container">
	<div class="dashboard-body">
		
		<div class="flat-panel">
	        
	        @if ($errors->has() OR Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif 

			<h4>填写充值金额</h4>

            {{ Form::open(array('url' => 'fund/add', 'role' => 'form', 'id' => 'frm')) }}
			<div class="row">
				<div class="col-sm-7">
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right ">账户余额</label>
						<div class="col-sm-4">
							{{ currencyFormat(empty($balance) ? 0 : $balance->available_amount) }}
						</div>
					</div>
								
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right mt5">充值金额</label>
						<div class="col-sm-4">
							<input type="text" name="amount" id="amount" class="form-control" value="{{ Input::old('amount') }}"
								data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入充值金额"
	                			data-bv-regexp="true"
	                			pattern="(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})$)"
	                			data-bv-regexp-message="* 请输入有效金额">
						</div>
					</div>
		
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right ">充值费用</label>
						<div class="col-sm-4 tooltip-container fund-fee">
							￥<span id='fund_fee'>0</span> 元
							<a class="custom-tooltips" href="javascript:;" 
									data-toggle="tooltip" 
									data-placement="bottom"
		                            title="充值费用按充值金额的 {{ $fee['rate'] }}% 由第三方平台收取，上限{{ currencyFormat($fee['max_amount']) }}，超出部分由天添财富承担">
								<i class="fa fa-question-circle lightblue"></i>
							</a>
						</div>
					</div>
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right">实际到账金额</label>
						<div class="col-sm-4">
							￥<span id='real_amount'>0</span> 元
						</div>
					</div>
					
				</div>
			
			
				<div class="col-sm-5">
					<div class="all-radius tip-block">
						温馨提示：
						<ul class="">
							<li>为了您的账户安全，请在充值前进行身份认证、手机绑定以及提现密码设置</li>
							<li>您的账户资金将通过第三方平台进行充值</li>
							<li>请注意您的银行卡充值限制，以免造成不便</li>
							<li>禁止洗钱、信用卡套现、虚假交易等行为，一经发现并确认，将终止该账户的使用</li>
							<li>如果充值金额没有及时到账，请联系我们</li>
						</ul>
					</div>
                </div>
			</div>

			<div class="text-center mt30 row">
				<span class="col-sm-4">
					<button href='/home' class="fund-btn button button-rounded button-flat-primary">确认充值</button> 或 {{ link_to('/home', '取消') }}         
				</span>	                                   
			</div>
			
            {{ Form::close() }}
            
		</div>
	</div>
</div>    
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/add-fund.js') }}

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });

    $("#amount").on('keyup change', function(){

    	var _rate		= {{ $fee['rate'] }};
    	var _max_amount	= {{ $fee['max_amount'] }};
    	
        var _amount = $(this).val();
        var _fee	= number_format(_amount * _rate * 0.01, 2);	// %
		if (_fee > _max_amount)
		{
			_fee = _max_amount;
		}
		
        $('#fund_fee').text(_fee);
        $('#real_amount').text(number_format(_amount - _fee, 2));
    });

    $("#amount").trigger('keyup');

})
</script>
@stop
