@section('title')
FX Receipt
@stop

@section('content')
    @include('lockrate.subnav')
    @include('breadcrumb')
    <div class="container">
        <div class="dashboard-body">
            <h2 class="form-title"><i class="fa fa-credit-card"></i>  My Deal Receipt</h2>
            <div class="receipt-number">
                <div><i class="f20p fa fa-clock-o"></i> {{ time_to_tz($receipt['created_at']) }}</div>
                <div class="receipt-id">Receipt number: <span>{{ $receipt['id'] }}</span></div>
            </div>
            <hr class="mt10">
            <div class="row">
                <div class="col-sm-9">
                    <div class="flat-panel">
                        {{ receiptTips($receipt['id']) }} 
                    </div>
                    <div>
                        <h5 class="color-666">Exchange Details</h5>
                        <p>You are transferring the {{ sprintf('%s %s', $receipt->fx['lock_amount'], $receipt->fx['lock_currency_have']) }}</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Local Amount</th>
                                    <th>Exchange Rate</th>
                                    <th>Foreign Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ sprintf('%s %s', $receipt->fx['lock_amount'], $receipt->fx['lock_currency_have']) }}</td>
                                    <td>{{ sprintf('1 %s = %s %s', $receipt->fx['lock_currency_have'], $receipt->fx['lock_rate'], $receipt->fx['lock_currency_want']) }}</td>
                                    <td>{{ sprintf('%s %s', $receipt->fx['lock_amount'] * $receipt->fx['lock_rate'], $receipt->fx['lock_currency_want']) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p>Transaction Fee $20 AUD</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <p class="text-right">Your Total Recharge</p>
                                        <p class="text-right"><strong>{{ sprintf('%s %s', $receipt->fx['lock_amount'] * $receipt->fx['lock_rate'] , $receipt->fx['lock_currency_want']) }}</strong></p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <hr>
                    </div>  
                </div>
                <div class="col-sm-3">
                    <div class="receipt-status label-{{ receiptStatusStyle($receipt['status']) }}">
                        <p class="f20p">Status</p>
                        <p>{{ $receipt->status }}</p>
                    </div>
                    <div style="text-align:center;" class="mt10">
                        <span class="fa fa-print"></span> <a href="javascript:;" onclick="javascript:window.print();">Print Receipt</a>
                    </div>
                </div>
            </div>  
        </div>
    </div>
@stop
