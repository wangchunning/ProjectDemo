@section('title')
我的账户
@stop

@section('body-tag')
<body class="my-account-overview">
@stop

@section('content')
<!--body-->
@include('home.subnav')
<div class="container flat-panel">
<div class="dashboard-body">
	
    
    @if ($errors->has() OR Session::has('error'))
    <div class="alert alert-danger">
        {{ $errors->has() ? $errors->first() : Session::get('error') }}
    </div>
    @endif  
    
	<!--base info-->
	@include('profile.welcome_header')
	<!-- /base info-->
   

	<!-- my balance -->
    <hr class="mt40">
    @include('home.balance.my_balance')
	<!-- /my balance -->
    
    <!-- mydebt -->
    <hr class="mt40">
	<h4>
		<i class="fa fa-th-list"></i> 我的借款
		<span class="pull-right f12p mt5"><a class="more-link" href="{{ url('/mydebt') }}">更多 ...</a></span>
	</h4>
	<div class="pannel-body table-responsive mt30">
		@include('home.mydebt.debt_list')
	</div>
    <!-- /mydebt -->

	<!-- myinvest -->
    <hr class="mt40">
	<h4>
		<i class="fa fa-calculator"></i> 我的投资
		<span class="pull-right f12p mt5"><a class="more-link" href="{{ url('/myinvest') }}">更多 ...</a></span>
	</h4>
	<div class="pannel-body table-responsive mt30">
		@include('home.myinvest.invest_list')
	</div>
    <!-- /myinvest -->
    
    <!-- My Recenty Transaction -->
  	<hr class="mt40">
        <h4>
        	<i class="fa fa-th-list"></i> 最近交易记录 <small class="color-999"> - </small>
        	<span class="pull-right f12p mt5"><a class="more-link" href="{{ url('my-tx-history') }}">更多 ...</a></span>
        </h4>

    	<div class="panel-body table-responsive">
    		<table class="table table-hover table-striped" id="recent_activity_table" >
    			<thead>
    				<tr>
    					<th>日期</th>  
    					<th class="unsortable">流水号</th>  
    					<th class="unsortable">描述</th>   									
    					<th class="unsortable">状态</th>
    					<th class="unsortable">总计<small class="grey"> (手续费)</small></th>          					
    				</tr>
    			</thead>
    			<tbody>
                {{-- $receipts->isEmpty() ? '<tr><td colspan="4" align="center">- 没有记录 -</td></tr>' : '' --}}            				
    			@foreach ($receipts as $receipt)
    				<tr onclick="window.location.href=('{{ $receipt->getReceiptUrl() }}')" style="CURSOR:pointer">
                        <td> {{ date_word($receipt->create_time) }} </td>
                        <td>{{ $receipt->deal_no }}</td>
    					<td>
    						<small class="grey f12p">{{ $receipt->type }}至</small><br>
                            {{ $receipt->transfer_to }}
    					</td>
        				<td>
                            {{ $receipt->fx_rate }} <br>
                            <small class="grey f12p">{{ $receipt->fx_currency_pair }}</small>
    					</td>  

    					<td>
    						<span class="label label-{{ receiptStatusStyle($receipt->status) }}">{{ $receipt->status_desc }}</span>
    					</td>
    					<td>
                            <strong>
                                @if ($receipt->amount_out > 0)
                                <font class="red">
                                    {{ '-' . currencyFormat($receipt->currency_out, $receipt->amount_out, true) }}
                                </font>
                                @endif                            
                                @if ($receipt->amount_in > 0)
                                    @if ($receipt->amount_out > 0)
                                    <br>
                                    @endif
                                <font class="green">
                                    {{ '+' . currencyFormat($receipt->currency_in, $receipt->amount_in, true) }}
                                </font>
                                @endif
                            </strong>
                            @if ($receipt->fee_amount != 0)
                            <br>
                            <small class="grey f12p">
                                (-{{ currencyFormat($receipt->fee_currency , $receipt->fee_amount, TRUE) }})
                            </small>
                            @endif         						  						
    					</td>
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
    	</div>

  <!-- /My Recenty Transaction -->
</div>

<!-- /body-->
@stop

@section('inline-js')

<script>
$(function(){

	init_data_table('content-table', false, false, '');	
	
})
        	
</script>


@stop
