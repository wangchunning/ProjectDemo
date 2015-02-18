@section('title')
抵押贷投资列表 - 天添财富
@stop

@section('content')
@include('breadcrumb')

<div class="container">
	<div class="white-box-body all-radius">
		<div class="flat-panel">
			
			<h4> 投资列表 <small class='ml10'><a target='_blank' href="/">理财计算器</a></small></h4>


            <div class="pannel-body table-responsive mt30">
               <table class="table table-hover table-striped client-debt-table f14p" id="content-table">
                    <thead>
                        <tr>
                        	<th class="unsortable">借款标题</th>
                        	<th class="unsortable">信用等级</th>
                        	<th class="unsortable">年利率</th> 
                            <th class="unsortable">借款金额</th>  
                            <th class="unsortable">期限</th>
                            <th class="unsortable">进度</th>
                            <th class="unsortable"></th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($txs as $tx)
						<tr>
							<td class="f16p"><a href="{{ Tx::detail_url($tx->receipt_number, $tx->id) }}">{{  $tx->title  }}</a></td>
							<td class="f16p">
								<span class="ml10 badge badge-{{CreditLevel::color($tx->credit_level)}}">
			            					{{ CreditLevel::desc($tx->credit_level) }}
			            		</span>
			            	</td>
			            	<td class="f18p">{{  $tx->rate  }}<small>%</small></td>
							<td class="f18p">{{ number_format($tx->total_amount) }}<small>元</small></td>
							<td class="f18p">{{ $tx->period_month }}<small>个月</small></td>
							<td class="f18p">-</td>
							<td class="f18p">
								
									@if ($tx->status == Tx::STATUS_LENDING)
									<a href="{{ Tx::detail_url($tx->receipt_number, $tx->id) }}"><span class="label label-{{ Tx::status_style($tx->status) }}">投标</span></a>
									@else	
									<span class="bg-transpant label label-{{ Tx::status_style($tx->status) }}">
										{{ Tx::status_desc($tx->status) }}	
									</span>
									@endif
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

	//init_data_table('content-table', false, false, '');
	
	
})
</script>
@stop


