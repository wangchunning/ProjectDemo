@section('title')
确认借款信息 - 天添财富
@stop

@section('content')
@include('breadcrumb')

{{ Form::open(array('url' => '/invest/pawn-confirm', 'role' => 'form', 'id' => 'frm')) }}
<div class="container">
    <div class="dashboard-body">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif           

        <div class="flat-panel receipt-item-panel mt20">   
            <h4>确认投资信息</h4>
            <p class="help-block">
               	 账户余额 {{ currencyFormat($balance->available_amount) }}    
            </p>
            
            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                        <dt>可投金额</dt>
                    	<dd>{{ currencyFormat($tx->total_amount - $tx->invest_amount) }} </dd>                    	
                    	<dt>投资金额</dt>
                    	<dd>{{ currencyFormat($gain_detail['principal']) }}</dd>
                    	<dt>预期收益</dt>
                    	<dd>{{ currencyFormat($gain_detail['interest']) }}</dd>
                    	<dt>借款期限</dt>
                    	<dd>{{ $tx->period_month }} 个月</dd>
                        <dt>收款明细</dt>
                        <dd>
                            <table class="table table-hover table-striped" id="content-table">
		                    <thead>
		                        <tr>
		                        	<th>月份</th>
		                        	<th class="unsortable">月收本息</th>
		                        	<th class="unsortable">月收本金</th>
		                        	<th class="unsortable">月收利息</th>
		                        	<th class="unsortable">待收本息</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@foreach ($gain_detail['detail'] as $detail)
								<tr>
									<td>{{ $detail['period_cnt'] }}月</td>
									<td>{{ currencyFormat($detail['total_amount']) }}</td>
									<td>{{ currencyFormat($detail['principal_amount']) }}</td>
									<td>{{ currencyFormat($detail['interest_amount']) }}</td>
									<td>{{ currencyFormat($detail['unpaid_total_amount']) }}</td>
								</tr>
								@endforeach
		                    </tbody>
		                </table>
                        </dd>
                    	
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
	
			<hr class="mb20">
			
	        <div class="ml20">
	            <button type="submit" class="button button-rounded button-flat-primary">确认投资</button>                                       
	        </div>
                    
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
