@section('title')
Balance
@stop

@section('content')
    @include('admin.customer.subnav')
    <div class="container">     
        <div class="dashboard-body">
            <h4>{{ $user->full_name }} <span class="back-btn f14p"><a href="{{ url('admin/customers/overview', $user->uid) }}" class="underline">Back</a></span></h4>
            <div class="flat-panel row">
                <div class="col-sm-6">
                    <h4> {{ sprintf('%s %s', currencyFlag($balance->currency), $balance->currency) }} Balance</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ul class="balance-ul">
                        <li>
                            <span>Balance</span>
                            {{ currencyFormat($balance->currency, $balance->amount) }}                                                    
                        </li>
                        <li>
                            <span>Available</span>
                            {{ currencyFormat($balance->currency, $balance->available_amount) }}                                                  
                        </li>                        
                    </ul>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction Detail</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $histories->isEmpty() ? '<tr><td colspan="5" align="center">- No Records to Display -</td></tr>' : ''}}                           
                    @foreach ($histories as $history)
                        <tr onclick="window.open('{{ url('admin/transaction/receipt', $history->receipt_id) }}')" style="CURSOR:pointer">
                            <td>{{ $history->created_at }}</td>
                            <td>To：{{ $history->receipt->transfer_to }}</td>
                            <td><span class="label label-{{ receiptStatusStyle($history->receipt->status) }}">{{ $history->receipt->status }}</span></td>
                            <td>{{ currencyFormat($balance->currency, $history->amount, FALSE, TRUE) }}</td>
                            <td>{{ currencyFormat($balance->currency, $history->pre_amount + $history->amount) }}</td>                            
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">{{ $histories->links(); }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>    
@stop
