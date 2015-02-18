 @section('title')
My Center
@stop

@section('content')
    @include('admin.center.subnav')
    @include('admin.manager_info')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
            @if (check_perm('view_rate', FALSE))
            <div id="rate-pannel">   
                <h2><i class="fa fa-money"></i> Spot Rate <!-- <span class="pull-right"><a class="underline white" href="javascript:;">Setting</a><span> --></h2>        
                <hr>
                <div class="pannel-body table-responsive rate" id="basic">
                    <input type="hidden" id="rate-stop" value="0">           
                    <table class="table table-hover table-striped rate-table">
                        <thead>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="2">Market Rate</th>
                                <th colspan="2">Wexchange Rate</th>
                                <th>Time</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th>Market Bid</th>
                                <th>Market Ask</th>
                                <th>Bid</th>
                                <th>Ask</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $output = array(); ?>    
                        @foreach ($rates as $rate)
                            <tr class="{{ $rate->from . $rate->to }}">
                                <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                                <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                                <td width="50" class="rate_change"></td>
                                <td class="market-bid"><img src="/assets/img/spinner.gif"></td>
                                <td class="market-ask"><img src="/assets/img/spinner.gif"></td>
                                <td class="vip-bid"><img src="/assets/img/spinner.gif"></td>
                                <td class="vip-ask"><img src="/assets/img/spinner.gif"></td>
                                <td class="time"><img src="/assets/img/spinner.gif"></td>
                            </tr>
                        <?php $output[] = $rate->from . $rate->to; ?>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="10"><a href="/admin/rate" class="more-btn">More...</a></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
            @if (check_perms('view_add_fund,view_withdraw,view_fx', 'OR', FALSE))
            <div>
                <h2><i class="fa fa-tasks"></i>  Lastest Transactions <!-- <span class="pull-right"><a class="underline white" href="javascript:;">Setting</a><span> --></h2>         
                <hr>
                <div class="pannel-body table-responsive">            
                    <table class="table table-hover table-striped transaction">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Transaction ID</th>
                                <th>Type</th>
                                <th>Payment Status</th>
                                <th>Gross</th>
                            </tr>  
                        </thead>
                        <tbody>
                            {{ $transactions->isEmpty() ? '<tr><td colspan="6" align="center">- No Records to Display -</td></tr>' : ''}}
                            @foreach ($transactions as $tran)
                            <tr>
                                <td>{{ date_word($tran->created_at) }}</td>
                                <td>{{ link_to('admin/customers/overview/' . $tran->user->uid, pretty_str($tran->user->full_name)) }}</td>
                                <td>
                                    @if (trans_perm($tran->type, NULL, FALSE))
                                    {{ link_to('/admin/transaction/detail/'.$tran->id, $tran->id) }}
                                    @else
                                    {{ $tran->id }}
                                    @endif
                                </td>
                                <td>{{ $tran->type }}</td>
                                <td>
                                    @if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                                        <span class="label label-danger">{{ sprintf('<i class="fa fa-exclamation-triangle"></i> %s', overdueCheck($tran->created_at)) }}</span>
                                    @else
                                        <span class="label label-{{ transactionStatusStyle($tran->status) }}">{{ $tran->status }}</span>
                                    @endif
                                </td>
                                <td class="amount">
                                    @if($tran->type == 'Deposit')
                                        <font class="green">+{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'FX Deal')
                                        <font class="green">{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'Withdraw')
                                        <font class="red">-{{ $tran->amountFormat }}</font>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td class="tcenter" colspan="6"><a href="/admin/transaction" class="more-btn">More...</a></td></tr>
                        </tfoot>
                    </table>
                </div>    
            </div>
            @endif
            @if (check_perm('view_customer', FALSE))
            <div>
                <h2><i class="fa fa-users"></i>  Lastest Customers <!-- <span class="pull-right"><a class="underline white" href="javascript:;">Setting</a><span> --></h2>        
                <hr>
                <div class="pannel-body table-responsive">            
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Value</th>
                                <th>Last Activity</th>
                                <th>Status</th>
                            </tr>  
                        </thead>
                        <tbody>
                            {{ $users->isEmpty() ? '<tr><td colspan="5">- No Records to Display -</td></tr>' : ''}}
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ link_to('admin/customers/overview/' . $user->uid, pretty_str($user->full_name)) }}
                                    {{ $user->business ? '<span class="label label-primary">Company</span>' : '' }}  
                                    @if (is_vip($user->profile->vip_type))                             
                                    <span class="label {{ vip_label_class($user->profile->vip_type) }}">
                                        {{ $user->profile->vip_type }}
                                    </span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>AUD {{ $user->balance_to_aud }}</td>
                                <td>{{ date_word($user->last_login) }}</td>
                                <td>{{ customerStatusStyle($user->status) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td class="tcenter" colspan="5"><a href="/admin/customers" class="more-btn">More...</a></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@stop

@section('inline-js')
@if (check_perm('view_rate', FALSE))
<script>
var tickList = "{{ implode($output, ',') }}";
var margins = $.parseJSON('{{ rateMarginJson($rates) }}');
</script>
{{ HTML::script('assets/js/lib/admin/rate.js') }}
@endif
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('assets/js/lib/admin/user.js') }}
@stop