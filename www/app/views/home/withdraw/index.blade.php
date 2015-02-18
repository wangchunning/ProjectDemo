@section('title')
取款
@stop

@section('content')
    <div class="container">
        <div class="dashboard-body">
            <div class="flat-panel">
                <h2>取款</h2>
                <p class="tips">
                    通过取款，您可以将您在 Anying 的资金转账到任意一个或多个收款人的银行账号
                </p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>选项</th>
                                <th>处理时长</th>
                                <th>费用</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="/withdraw/add">到其它银行账号</a></td>
                                <td>澳币到澳洲银行<br>1-2 工作日</td>
                                <td>免费</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>其它币种或其它银行<br>3-4 工作日</td>
                                <td>根据您的会员等级实时计算</td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <h4>我的余额</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>币种</th>                               
                            <th>账号余额</th>
                            <th>可用余额</th>
                        </tr>
                    </thead>    
                    <tbody>
                    @if ($balances->isEmpty())
                    <tr><td colspan="3" align="center">- 没有记录 -</td></tr>
                    @else
                        @foreach ($balances as $key => $balance)
                            <tr onclick="window.open('{{ url('withdraw/add', $balance->currency) }}', '_top')" style="CURSOR:pointer">                           
                                <td>{{ currencyFlag($balance->currency)}}{{ $balance->currency }}</td>
                                <td>{{ currencyFormat($balance->currency, $balance->amount) }}</td>
                                <td>{{ currencyFormat($balance->currency, $balance->available_amount) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="info">
                                <small class="pull-left grey">* 总余额（所有账号余额兑换成澳元）</small>
                                <span class="pull-right f16p">可用余额总计 <strong>≈ {{ totalAUD($balances, $rates) }} AUD</strong></span>
                            </td>
                        </tr>
                    @endif
                    </tbody>                
                </table>                
            </div>
        </div>
    </div>    
@stop
