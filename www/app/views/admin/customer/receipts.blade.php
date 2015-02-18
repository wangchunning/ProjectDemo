@section('title')
Receipts
@stop

@section('content')
    @include('admin.customer.subnav')
    <div class="container">     
        <div class="dashboard-body">
            <h4>{{ $user->full_name }} <span class="back-btn f14p"><a href="{{ url('admin/customers/overview', $user->uid) }}" class="underline">Back</a></span></h4>
            <h4 class="mt30">Receipts</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped transaction">
                    <thead>
                        <tr>
                            <th>Date</th>                               
                            <th>To</th>
                            <th>Receipt No.</th>
                            <th>Status</th>
                            <th>Amounts</th>
                        </tr>
                    </thead>    
                    <tbody>
                    {{ $receipts->isEmpty() ? '<tr><td colspan="5" align="center">- No Records to Display -</td></tr>' : '' }}
                    @foreach ($receipts as $key => $receipt)
                        <tr onclick="window.open('{{ url('admin/transaction/receipt', $receipt->id) }}')" style="CURSOR:pointer">                           
                            <td>{{ date_word($receipt->created_at) }}</td>
                            <td>{{ $receipt->transfer_to }}</td>
                            <td>{{ $receipt->id }}</td>
                            <td>
                                @if ($receipt->status == 'Waiting for fund' AND overdueCheck($receipt->created_at))
                                    <span class="label label-danger">{{ sprintf('<i class="fa fa-exclamation-triangle"></i> %s', overdueCheck($receipt->created_at)) }}</span>
                                @else
                                    <span class="label label-{{ receiptStatusStyle($receipt->status) }}">{{ $receipt->status }}</span>
                                @endif
                            </td>
                            <td class="amount">
                                @if($receipt->type == 'Deposit')
                                    <font class="green">+{{ $receipt->amount }}</font>
                                @elseif($receipt->type == 'FX Deal')
                                    <font class="green">{{ $receipt->amount }}</font>
                                @else
                                    <font class="red">-{{ $receipt->amount }}</font>
                                @endif
                            </td>                                                                                
                        </tr>
                    @endforeach
                    </tbody>    
                    <tfoot>
                        <tr>
                            <td colspan="5">{{ $receipts->links() }}</td>
                        <tr>
                    </tfoot> 
                </table>                    
            </div>
        </div>
    </div>    
@stop
