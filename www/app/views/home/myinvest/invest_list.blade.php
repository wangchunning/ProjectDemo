               <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                        	<th>日期</th>
                        	<th class="unsortable">项目</th>
                        	<th class="unsortable">投资金额</th>
                            <th class="unsortable">投资收益</th> 
                            <th class="unsortable">已收收益</th>                          
                            <th class="unsortable">待收本金</th>
                            <th class="unsortable">状态</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($invests as $invest)
						<tr>
							<td>{{ pretty_date($invest->created_at) }}</td>
							<td><a href="{{ Tx::detail_url($invest->receipt_number, $invest->id) }}">{{  $invest->tx_title  }}</a></td>
							<td>{{  currencyFormat($invest->invest_amount)  }}</td>
							<td>{{ currencyFormat($invest->interest_amount) }}</td>
							<td>{{  currencyFormat($invest->paid_interest_amount)  }}</td>
							<td>{{ currencyFormat($invest->unpaid_principal_amount) }}</td>
							<td>
								<span class="label label-{{ Tx::status_style($invest->status) }}">
									{{ Tx::status_desc($invest->status) }}
								</span>
							</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>