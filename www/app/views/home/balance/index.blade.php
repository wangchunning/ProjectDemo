@section('title')
账户余额流水
@stop

@section('content')
@include('home.subnav')
@include('breadcrumb')

    <div class="container">     
        <div class="dashboard-body">
            <div class="flat-panel row">
                <div class="col-sm-4">
                    <h4><i class="fa fa-table"></i> 账户流水</h4>
                </div>
                <div class="col-sm-8 text-right">
                    <ul class="balance-ul">
                        <li>
                            <span>账户总余额</span>
                            {{ currencyFormat($balance->total_amount) }}                                                    
                        </li>
                        <li>
                            <span>可用余额</span>
                            {{ currencyFormat($balance->available_amount) }}                                                  
                        </li>                        
                    </ul>
                </div>
            </div>
            <div class="table-responsive mt20">
                <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>类别</th>
                            <th class="unsortable">存入金额</th>
                            <th class="unsortable">转出金额</th>
                            <th class="unsortable">冻结金额</th>
                            <th class="unsortable">可用余额</th>                        
                        </tr>
                    </thead>
                    <tbody>                       
                   		@foreach ($histories as $history)
                        <tr>
                            <td>{{ $history->created_at }}</td>
                            <td>{{ $history->type_desc() }}</td>
                            <td>{{ $history->credit_amount == 0 ? '' : currencyFormat($history->credit_amount, '+', true) }}</td>
                            <td>{{ $history->debt_amount == 0 ? '' : currencyFormat($history->debt_amount, '-', true) }}</td>
                            <td>{{ $history->frozen_amount == 0 ? '' : currencyFormat($history->frozen_amount, '-', true) }}</td>   
                            <td>{{ currencyFormat($history->available_balance) }}</td>                            
                        </tr>
                    	@endforeach
                    </tbody>                  
                </table>
            </div>
        </div>
    </div>    
@stop

@section('inline-js')
{{ HTML::script('assets/js/vendor/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/data_table.js') }}

<script>
$(function(){
	init_data_table('content-table', false, false, '');
	
})
</script>
@stop
