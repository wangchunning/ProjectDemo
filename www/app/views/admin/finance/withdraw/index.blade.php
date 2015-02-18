@section('title')
提现记录
@stop

@section('content')
@include('admin.finance.subnav')

<div class="container">
	<div class="white-box-body all-radius">
		<div class="flat-panel">
			
			<h4><i class="fa fa-users"></i> 提现记录</h4>

			<hr class="mt20 mb20">

			{{ Form::open(array('url' => '/admin/finance/withdraw', 'method' => 'get', 'role' => 'form', 'id' => 'frm', 'class' => 'form-horizontal')) }}
			@include('partials.table_search_group')
			{{ Form::close() }}

            <div class="pannel-body table-responsive mt30">
               <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                        	<th>日期</th>
                        	<th class="unsortable">流水号</th>
                            <th class="unsortable">用户名</th> 
                            <th class="unsortable">银行账号</th>                          
                            <th class="unsortable">实际到账金额</th>
                            <th class="unsortable">提现手续费</th>
                            <th class="unsortable">状态</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($user_txs as $user_tx)
						<tr>
							<td>{{ pretty_date($user_tx->created_at) }}</td>
							<td>{{ link_to(url('/admin/finance/withdraw-detail', $user_tx->id), $user_tx->receipt_number) }}</td>
							<td>{{ $user_tx->user_name }}</td>
							<td>{{ pretty_account_number($user_tx->account_number) }}</td>
							<td>{{ currencyFormat($user_tx->available_amount) }}</td>
							<td>{{ currencyFormat($user_tx->fee_amount) }}</td>
							<td>
								<span class="label label-{{ WithdrawTx::status_style($user_tx->status) }}">
									{{ WithdrawTx::status_desc($user_tx->status) }}
								</span>
							</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $user_txs->links() }}
            </div>
        </div>
    </div>
@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function(){

    $('select').selectBoxIt({
		theme: 'bootstrap',
		autoWidth: false
    });

    $(".datepicker").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language:'zh-CN',
        minView: 2,
        viewSelect: 2
    });

	init_data_table('content-table', false, false, '');
	
	
})
</script>
@stop


