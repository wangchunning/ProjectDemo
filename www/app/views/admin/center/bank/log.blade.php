@section('title')
Bank Logs
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">     
        <div class="dashboard-body">
            <h4 class="mb30">{{ sprintf('%s %s', $bank->bank, $bank->branch ? '(' . $bank->branch .')': '') }} <span class="back-btn f14p"><a href="javascript:;" onclick="javascript:history.back(-1);" class="underline">Back</a></span></h4>
            <div class="row">
                <div class="col-sm-9">
                    <h4>Bank Logs</h4>
                </div>
                <div class="col-sm-2">
                    <a href="javascript:;" class="btn btn-primary" id="new-bank-log-btn">Add Bank Log</a>
                </div>
            </div>
            <hr class="mt0"> 

            <div id="new-bank-log-input" class="">
                <form class="form-inline" role="form">
                <div class="form-group">
                    <select name="new_bank_log_type" class="form-control">
                        <option value="deposit">Deposit</option>
                        <option value="withdraw" selected>Withdraw</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="new_bank_log_amount" class="form-control" placeholder="Amount"/>
                </div>
                <div class="form-group mr10">
                    <input type="text" name="new_bank_log_desc" class="form-control desc" placeholder="Desctiption"/>
                </div>
                <div class="form-group">
                    <a href="javascript:;" data-id="{{$bank->id}}" data-currency="{{$currency}}"
                        class="btn btn-primary" id="new-bank-log-save-btn">Update</a> 
                    <a href="javascript:;" class="btn btn-default" id="new-bank-log-cancel-btn">Cancel</a>
                </div>
                </form>
                <div class="inline color-error" id="form-error-tip"></div>
            </div>


            <div class="table-responsive">
                <table class="table table-hover table-striped bank-log">
                    <thead>
                        <tr>
                            <th>Date</th>                               
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Transaction</th>                           
                            <th>Description</th>
                        </tr>
                    </thead>    
                    <tbody>
                    {{ $logs->isEmpty() ? '<tr><td colspan="5" align="center">- No Records to Display -</td></tr>' : '' }}
                    @foreach ($logs as $log)
                        <tr>                           
                            <td>{{ date_word($log->created_at) }}</td>
                            <td>{{ $log->amount >= 0 ? 
                                    '<font class="green">'. currencyFormat($log->currency, $log->amount,true, true) . '</font>' : 
                                    '<font class="red">' .  currencyFormat($log->currency, $log->amount,true, true). '</font>' }}
                            </td>
                            <td>
                                {{ currencyFormat($log->currency, $log->current_amount, true) }}
                            </td>
                            <td>
                                @if ($log->transaction_id == 'manual_oper')
                                Manual Operation
                                @else
                                {{ link_to('/admin/transaction/detail/'.$log->transaction_id, $log->transaction_id) }}
                                @endif
                            </td>                           
                            <td class="log-desc">
                                {{ $log->memo ? $log->memo : ($log->amount > 0 ? 'Add Fund ' . currencyFormat($log->currency, $log->amount,true) . ' to Balance' : 'Withdraw ' . currencyFormat($log->currency, abs($log->amount),true) . ' From Balance') }}
                            </td>                                                                               
                        </tr>
                    @endforeach
                    </tbody>    
                    <tfoot>
                        <tr>
                            <td colspan="5">{{ $logs->links() }}</td>
                        <tr>
                    </tfoot> 
                </table>                    
            </div>
        </div>
    </div>    
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/admin/bank-log.js')}}
<script>
    $(function() {
        $('.table').delegate('.log-desc', 'click', function(){
            $(this).removeClass('log-desc');
        });
    });
</script>
@stop
