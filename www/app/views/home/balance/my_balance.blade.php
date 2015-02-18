          <!-- Account balance -->
    
          <div id="flat-pannel">
            <h4><i class="fa fa-usd"></i> 我的余额
            	<span class="pull-right f12p mt5"><a class="more-link" href="{{ url('/home/balance') }}">查看流水 ...</a></span>
            </h4>
            <div class="panel-body table-responsive mt20">
                <table class="table table-hover table-striped balance-table">
                    <thead>
                        <tr>
                            <th></th>                             
                            <th>可用余额 
                                <a href="javascript:;" class="custom-tooltips" data-toggle="tooltip"
                                    title="可以进行取款、投资操作的金额">
                                    <i class="fa fa-question-circle lightblue"></i>
                                </a>
                            </th>
                            <th>冻结金额
                                <a href="javascript:;" class="custom-tooltips" data-toggle="tooltip"
                                    title="未清算金额">
                                    <i class="fa fa-question-circle lightblue"></i>
                                </a>
                            </th>
                            <th>总金额 
                                <a href="javascript:;" class="custom-tooltips" data-toggle="tooltip"
                                    title="包含未清算金额的总金额">
                                    <i class="fa fa-question-circle lightblue"></i>
                                </a>
                            </th>                           
                        </tr>
                    </thead>    
                    <tbody>
                    @if (empty($balance))
                    <tr><td colspan="5" align="center">-- 没有记录 --</td></tr>
                    @else
                        <tr onclick="window.location.href=('{{ url('/home/balance') }}')" style="CURSOR:pointer">                           
                            <td></td>
                            <td>{{ currencyFormat($balance->available_amount) }}</td>
                            <td>{{ currencyFormat($balance->frozen_amount) }}</td>
                            <td>{{ currencyFormat($balance->total_amount) }}</td>
					</tr>
                    @endif
                    </tbody>                
                </table>
            </div>
          </div>
          <!-- /Account balance -->