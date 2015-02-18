@section('title')
借款记录
@stop

@section('content')
@include('admin.debt.subnav')

<div class="container">
	<div class="white-box-body all-radius">
		<div class="flat-panel">
			
			<h4><i class="fa fa-users"></i> 借款记录</h4>

			<hr class="mt20 mb20">

			{{ Form::open(array('url' => '/admin/debt', 'method' => 'get', 'role' => 'form', 'id' => 'frm', 'class' => 'form-horizontal')) }}
			@include('partials.table_search_group')
			{{ Form::close() }}

            <div class="pannel-body table-responsive mt30">
               <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                        	<th>日期</th>
                        	<th class="unsortable">用户名</th>
                        	<th class="unsortable">借款标题</th>
                        	<th class="unsortable">借款类型</th>
                            <th class="unsortable">借款金额</th> 
                            <th class="unsortable">年利率</th>                          
                            <th class="unsortable">还款期限</th>
                            <th class="unsortable">状态</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($txs as $tx)
						<tr>
							<td>{{ pretty_date($tx->created_at) }}</td>
							<td>{{  $tx->user_name  }}</td>
							<td><a href="{{ Tx::detail_url($tx->receipt_number, $tx->id, 'admin') }}">{{  $tx->title  }}</a></td>
							<td>{{  Tx::type_desc($tx->receipt_number)  }}</td>
							<td>{{ currencyFormat($tx->total_amount) }}</td>
							<td>{{  $tx->rate  }}%</td>
							<td>{{ $tx->period_month }} 个月</td>
							<td>
								<span class="label label-{{ Tx::status_style($tx->status) }}">
									{{ Tx::status_desc($tx->status) }}
								</span>
							</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $txs->links() }}
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


