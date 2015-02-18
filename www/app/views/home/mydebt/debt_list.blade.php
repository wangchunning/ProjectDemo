               <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                        	<th>日期</th>
                        	<th class="unsortable">借款标题</th>
                        	<th class="unsortable">借款类型</th>
                            <th class="unsortable">借款金额</th> 
                            <th class="unsortable">年利率</th>                          
                            <th class="unsortable">还款期限（月）</th>
                            <th class="unsortable">月还款额 (元)</th>
                            <th class="unsortable">状态</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($txs as $tx)
						<tr>
							<td>{{ pretty_date($tx->created_at) }}</td>
							<td><a href="{{ Tx::detail_url($tx->receipt_number, $tx->id) }}">{{  $tx->title  }}</a></td>
							<td>{{  Tx::type_desc($tx->receipt_number)  }}</td>
							<td>{{ currencyFormat($tx->total_amount) }}</td>
							<td>{{  $tx->rate  }}%</td>
							<td>{{ $tx->period_month }}</td>
							<td>{{ currencyFormat($tx->month_repayment_total_amount) }}</td>
							<td>
								<span class="label label-{{ Tx::status_style($tx->status) }}">
									{{ Tx::status_desc($tx->status) }}
								</span>
							</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>