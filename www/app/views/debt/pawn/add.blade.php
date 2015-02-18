@section('title')
填写借款申请信息 - 天添财富
@stop

@section('content')
@include('breadcrumb')

<div class="container">
	<div class="dashboard-body">
		
		<div class="flat-panel">
	        
	        @if ($errors->has() OR Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif 

			<h4>填写借款申请信息</h4>

            {{ Form::open(array('url' => '/debt/pawn-add', 'role' => 'form', 'id' => 'frm')) }}
            
			<div class="form-group row mt40">
				<label for="amount" class="col-sm-2 text-right mt5">借款标题</label>
				<div class="col-sm-4 pr0">
					<input type="text" name="title" id="title" class="form-control" value="{{ Input::old('title') }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入借款标题"
                		data-bv-stringlength="true"
                		maxlength="14"
                		data-bv-stringlength-message="* 最多 14 个字">
				</div>
			</div>

			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right mt5">借款金额</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="amount" id="amount" class="form-control" value="{{ Input::old('amount') }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入借款金额"
                		data-bv-between="true"
                		data-bv-between-inclusive="true"
                		data-bv-between-max="{{ $tx_config['credit_amount_max'][$user->credit_level] }}"
                		data-bv-between-min="{{ $tx_config['credit_amount_min'] }}"
                		data-bv-between-message="* 可借款金额范围 {{ $tx_config['credit_amount_min'] }} ~ {{ $tx_config['credit_amount_max'][$user->credit_level] }}"
                		data-bv-regexp="true"
                		pattern="^[1-9]\d*(00|50)$"
                		data-bv-regexp-message="* 借款金额只能是 50 的整数倍">
                		<span class="form-control-feedback">元</span>
				</div>
			</div>
			<div class="form-group row mt20">
				<label class="col-sm-2 mt5 text-right" for="education">借款期限</label>
				<div class="col-sm-4 pr0">
					<select name="period" id='period' class='form-control'>
						@foreach ($tx_config['period'] as $key => $val)
						<option value="{{$key}}" data-min-rate={{$val}}>{{$key}} 个月</option>
						@endforeach
					</select>
				</div>
        	</div>
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right mt5">年化利率</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="rate" id="rate" class="form-control" value="{{ Input::old('rate') }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入年化利率"
                		data-bv-between="true"
                		data-bv-between-inclusive="true"
                		data-bv-between-max="{{ $tx_config['rate_max'] }}"
                		data-bv-between-min="{{ $tx_config['rate_min'] }}"
                		data-bv-between-message="* 年化利率范围 {{ $tx_config['rate_min'] }}% ~ {{ $tx_config['rate_max'] }}%">
                		<span class="form-control-feedback">%</span>
				</div>
			</div> 
			       			        	
			<div class="form-group row mt20">
				<label for="fee" class="col-sm-2 text-right ">还款方式</label>
				<div class="col-sm-4 tooltip-container">
					{{ Repayment::desc($tx_config['repayment_type']) }}
					<a class="custom-tooltips" href="javascript:;" 
							data-toggle="tooltip" 
                            title="{{ Repayment::detail($tx_config['repayment_type']) }}">
						<i class="fa fa-question-circle lightblue"></i>
					</a>
				</div>
			</div>
			@if ($tx_config['frozen_rate'] > 0)
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right">冻结保证金</label>
				<div class="col-sm-4 tooltip-container">
					<span class="blue">￥<span id='frozen_amount'>0.00</span> 元</span>
					<a class="custom-tooltips" href="javascript:;" 
							data-toggle="tooltip" 
                            title="借款成功后保证金会被暂时冻结，还清所有贷款后会退回到您的账户余额">
						<i class="fa fa-question-circle lightblue"></i>
					</a>
				</div>
			</div>
			@endif
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right">每月还本金及利息</label>
				<div class="col-sm-4">
					<span class="orange">￥<span id='month_repayment_investor_amount'>0.00</span> 元</span> (支付给理财人)
				</div>
			</div>
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right">每月借款管理费</label>
				<div class="col-sm-4">
					<span class="orange">￥<span id='month_repayment_fee_amount'>0.00</span> 元 </span> (支付给天添财富)
				</div>
			</div>
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right">初期服务费</label>
				<div class="col-sm-9">
					<span class="orange">￥<span id='service_fee_amount'>0.00</span> 元</span> (由天添财富收取)
					<div class="row mt30 f12p">
						<div class='col-sm-2 mt5'>信用等级</div>
						<div class='col-sm-10'>
							<div class="row">
							@foreach ($credit_config as $key => $data)
							<div class='col-sm-2'>
							<span class="badge badge-{{$data['color']}} mr30">{{ CreditLevel::desc($key) }}</span>
							</div>
							@endforeach
							</div>
						</div>	
					</div>
					<hr>
					<div class="row f12p">
						<div class='col-sm-2'>服务费率</div>
						<div class='col-sm-10'>
							<div class="row">
								@foreach ($credit_config as $key => $data)
								<div class='col-sm-2'>
									<span class="pl5">{{ $data['service_fee_rate'] }}%</span>
								</div>
								@endforeach
							</div>
						</div>	
					</div>					
				</div>
			</div>
			<div class="form-group row mt30">
				<label for="amount" class="col-sm-2 text-right mt5">借款描述</label>
				<div class="col-sm-6 pr0">
					<textarea id="desc" name="desc" class="form-control" placeholder="20 ~ 500个字" rows="5"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入借款描述"
                		data-bv-stringlength="true"
                		minlength="20"
                		maxlength="500"
                		data-bv-stringlength-message="* 20 ~ 500 个字">{{ Input::old('desc', '') }}</textarea>
				</div>
			</div> 						
																
			<hr>
			<div class="ml20 mt30 row">
				<span class="col-sm-4">
					<button type="submit" class="button button-rounded button-flat-primary">下一步</button> 或 {{ link_to('/debt', '取消') }}         
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

    $('#period').change(function(){
		var _min_rate 	= $(this).find("option:selected").data('min-rate');
		var _max_rate 	= {{ $tx_config['rate_max'] }};
		var _message	= '* 年化利率范围 ' + _min_rate + '% ~ ' + _max_rate + '%';
		if (_min_rate == _max_rate)
		{
			_message	= '* 年化利率只能是 ' + _min_rate + '%';
		}
		
		$('#frm').bootstrapValidator('updateOption', 'rate', 'between', 'min', _min_rate)
					.bootstrapValidator('updateOption', 'rate', 'between', 'message', _message)
					.bootstrapValidator('revalidateField', 'rate');

    });
    
    $("#amount,#rate,#period").on('change', function(){

        var _amount = $("#amount").val();
        var	_rate	= $("#rate").val();
        var	_period = $("#period").val();
        
        if (_amount == '' || _amount == 0 || 
        		_rate == '' || _rate == 0 || 
        		$('#frm .form-group').hasClass('has-error')) 
        {
            return;
        }

		var _uid = {{$user->uid}};
		var _product	= "{{ Tx::PRODUCT_TYPE_B2P }}";
		
	    $.ajax({
	        url 	: '/repayment/calc-repayment',
	        type	: 'post',
	        data	: {amount: _amount, period: _period, rate: _rate, uid: _uid, product: _product},
	        error	: function(rep)
	        {
	        	$.fancybox.hideLoading();
	        	$("button[type='submit']").prop('disabled', false);
	        	return '';
	        },
	        success : function(rep) 
	        {
	            $.fancybox.hideLoading();
	            $("button[type='submit']").prop('disabled', false);
	            
	            if (rep.status == 'ok') 
	            {
	            	_ret = rep.data.data;
	            	$('#frozen_amount').text(number_format(_ret.frozen_amount, 2));
	                $('#month_repayment_investor_amount').text(number_format(_ret.month_repay_to_investor, 2));
	                $('#month_repayment_fee_amount').text(number_format(_ret.month_repay_fee, 2));
	                $('#service_fee_amount').text(number_format(_ret.service_fee, 2));
	            };
	            
	        },
	        beforeSend : function() 
	        {
	            $.fancybox.showLoading();
	            $("button[type='submit']").prop('disabled', true);
	        }
	    });
		
		//$('input[type="hidden"][name="fee"]').val(_fee.toFixed(2));
		//$('input[type="hidden"][name="real_amount"]').val(_real_amount.toFixed(2));

    });

    $("#amount,#rate,#period").trigger('change');
})
</script>
@stop
