@section('title')
提现 - 天添财富
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

			<h4>填写提现金额</h4>

            {{ Form::open(array('url' => 'withdraw/add', 'role' => 'form', 'id' => 'frm')) }}
            <input type="hidden" name="fee" value="0">
            <input type="hidden" name="real_amount" value="0">
            
			<div class="row">
				<div class="col-sm-7">
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right ">账户余额</label>
						<div class="col-sm-4">
							{{ currencyFormat($balance->available_amount) }}
						</div>
					</div>
								
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right mt5">提现金额</label>
						<div class="col-sm-4">
							<input type="text" name="amount" id="amount" class="form-control" value="{{ Input::old('amount') }}"
								data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入提现金额"
	                			data-bv-regexp="true"
	                			pattern="(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})$)"
	                			data-bv-regexp-message="* 请输入有效金额"
	                			data-bv-lessthan-inclusive="true"
	                			data-bv-lessthan-value="{{ $balance->available_amount }}"
	                			data-bv-lessthan-message="* 您的账户余额不足">
						</div>
					</div>
		
					<div class="form-group row mt20">
						<label for="fee" class="col-sm-3 text-right ">提现费用</label>
						<div class="col-sm-4 tooltip-container withdraw-fee">
							￥<span id='fee'>0.00</span> 元
							<a class="custom-tooltips" href="javascript:;" 
									data-toggle="tooltip" 
		                            title="{{ 
		                            		sprintf('> 提现费用由第三方银行收取，超出部分由天添财富承担<br>>%s以下，%s/笔<br>>%s（包含） - %s，%s/笔<br>>%s以上（包含），%s/笔',
		                            				currencyFormat($fee[0]['range_max']), currencyFormat($fee[0]['amount']),
		                            				currencyFormat($fee[1]['range_min']), currencyFormat($fee[1]['range_max']), currencyFormat($fee[1]['amount']),
		                            				currencyFormat($fee[2]['range_min']), currencyFormat($fee[2]['amount'])
		                            		) 
		                            }}">
								<i class="fa fa-question-circle lightblue"></i>
							</a>
						</div>
					</div>
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right">实际扣除金额</label>
						<div class="col-sm-4">
							￥<span id='real_amount'>0.00</span> 元
						</div>
					</div>

					
					<hr class="mt40">
					<h4>收款账户信息</h4>
					<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right ">持卡人姓名</label>
						<div class="col-sm-4">
							{{ pretty_real_name($profile->real_name) }}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 mt5 text-right" for="education">银行名称</label>
						<div class="col-sm-5">
							{{ Form::select('bank_name', UserBankAccount::bank_arr(), 
									Input::old('bank_name') ? Input::old('bank_name') : (empty($bank_account) ? '' : $bank_account->bank_name), array('class' => 'form-control')) }}
						</div>
        			</div>
					<div class="form-group row">
						<label class="col-sm-3 mt5 text-right" for="bank_full_name">分行名称</label>
						<div class="col-sm-5">
							<input type="text" name="bank_full_name" class="form-control" value="{{ Input::old('bank_full_name') ? Input::old('bank_full_name') : (empty($bank_account) ? '' : $bank_account->bank_full_name) }}"
								data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入分行名称">
						</div>
        			</div>						
        			<div class="form-group row mt20">
						<label for="amount" class="col-sm-3 text-right mt5">银行卡号</label>
						<div class="col-sm-5">
							<input type="text" name="account_number" class="form-control" value="{{ Input::old('account_number') ? Input::old('account_number') : (empty($bank_account) ? '' : $bank_account->account_number) }}"
								data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入银行卡号"
	                			data-bv-regexp="true"
	                			pattern="(^\d{12,19}$)"
	                			data-bv-regexp-message="* 请输入有效银行卡号">
						</div>
					</div>	
																
				</div>
			
				<div class="col-sm-5">
					<div class="all-radius tip-block">
						温馨提示：
						<ul class="">
							<li>请确保您输入的提现金额，以及银行帐号信息准确无误</li>
							<li>如果您填写的提现信息不正确可能会导致提现失败，由此产生的提现费用将不予返还</li>
							<li>在双休日和法定节假日期间，用户可以申请提现，天添财富会在下一个工作日进行处理。由此造成的不便，请多多谅解！</li>
							<li>平台禁止洗钱、信用卡套现、虚假交易等行为，一经发现并确认，将终止该账户的使用</li>
						</ul>
					</div>
                </div>
			</div>
			<hr>
			<div class="ml20 mt30 row">
				<span class="col-sm-4">
					<button type="submit" class="withdraw-btn button button-rounded button-flat-primary">下一步</button> 或 {{ link_to('/home', '取消') }}         
				</span>	                                   
			</div>
			
            {{ Form::close() }}
            
		</div>
	</div>
</div>    
@stop

@section('inline-js')

{{-- HTML::script('assets/js/confirm_modal.js') --}}
{{-- HTML::script('assets/js/lib/add-withdraw.js') --}}

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });
    
    //init_confirm_modal('withdraw-btn', '确定要提现？', 'submit', '#frm');
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth : false
    });
    
    $("#amount").on('keyup change', function(){

        var _amount = $(this).val();
        if (_amount == 0)
        {
            return;
        }

    	var _range_0_max = {{ $fee[0]['range_max'] }};
    	var _range_1_min = {{ $fee[1]['range_min'] }};
    	var _range_1_max = {{ $fee[1]['range_max'] }};
    	var _range_2_min = {{ $fee[2]['range_min'] }};

    	var _range_0_amount = {{ $fee[0]['amount'] }};
    	var _range_1_amount = {{ $fee[1]['amount'] }};
    	var _range_2_amount = {{ $fee[2]['amount'] }};

        var _fee	= _range_1_amount;	//
		if (_amount < _range_0_max)
		{
			_fee = _range_0_amount;
		}
		else if (_amount >= _range_2_min)
		{
			_fee = _range_2_amount;
		}

		var _real_amount = parseFloat(_amount) + parseFloat(_fee);
		
		$('input[type="hidden"][name="fee"]').val(_fee.toFixed(2));
		$('input[type="hidden"][name="real_amount"]').val(_real_amount.toFixed(2));
		
        $('#fee').text(number_format(_fee, 2));
        $('#real_amount').text(number_format(_real_amount, 2));
    });

})
</script>
@stop
