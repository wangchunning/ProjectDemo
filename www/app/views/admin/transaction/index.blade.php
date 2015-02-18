@section('title')
Transactions
@stop

@section('content')
    @include('admin.transaction.subnav')
    <div class="container">
        <div class="white-box-body all-radius">
            <div class="flat-panel">
                <h2><i class="fa fa-tasks"></i>
                	{{ 
                		$type ? sprintf('%s %s Transactions', $uname, $type) : 
                				sprintf('All %s Transactions', $uname) 
                	}}
                </h2>
                <div class="tran-filter">
                    <div class="btn-group">
                        <button type="button" id='status_button' class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {{ Input::get('status', 'All Payment Status') }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>{{ link_to('admin/transaction', 'All Payment Status') }}</li>
                            @foreach ($status as $st)
                            <li>{{ link_to(current_url() . '?status=' . urlencode($st), $st) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" id='date_word_button' class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {{ Input::get('dw') ? commonDate(Input::get('dw')) : 'All Date' }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @foreach (commonDate() as $k => $dw)
                            <li>{{ link_to(current_url(TRUE, array('dw', 'page')) . '&dw=' . $k, $dw) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <form class="navbar-form date-pick" action="{{ current_url(TRUE,array('start', 'expriy')) }}" role="search">
                        <div class="form-group">
                            <input type="text" id="start" name="start" value="{{ Input::get('start') }}" class="form-control datepicker">
                            <span class="fa fa-calendar"></span>
                        </div>
                         to 
                        <div class="form-group">
                            <input type="text" id="expriy" name="expriy" value="{{ Input::get('expriy') }}" class="form-control datepicker">
                            <span class="fa fa-calendar"></span>
                        </div>
                        <button type="button" id="date_search" class="btn btn-default">Show</button>
                    </form>
                </div>
                <div class="tran-filter row mb5">
	                <!-- search -->
	                <div class="btn-group">
                    	<form class="navbar-form text-search" action="{{ current_url(TRUE, array('search')) }}" method="get" role="search">
	            		<div class="col-sm-5">                    	
	            			<div class="input-group " id="">
	            				<input type="search" id="search" name="search" value="{{Input::get('search')}}" placeholder="Customer Name or Receipt Number" class="form-control">
	            				<span class="input-group-btn">
	                        		<button class="btn btn-default" id="text_search"  type="button"><i class="fa fa-search"></i></button>
	                    		</span> 
	            			</div>
            			</div>
                    	</form>
                    </div><!-- /search -->
	            </div>

            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped transaction contents-list">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Receipt Number</th>
                            <th>
                                <?php
                                    switch ($type) 
                                    {
                                        case 'Deposit':
                                            echo 'Deposit to';
                                            break;
                                        
                                        case 'FX Deal':
                                            echo 'Rate';
                                            break;

                                        case 'Withdraw':
                                            echo 'Withdraw to';
                                            break;    
                                        default:
                                            echo 'Type';
                                            break;
                                    }
                                 ?>
                            </th>
                            @if ($type !== 'FX Deal')
                            <th> Status </th>
                            @endif
                            <th>
                                @if ($type == 'FX Deal' OR $type == 'Withdraw')
                                    Gross
                                @else
                                    Amount
                                @endif                
                            </th>
                            @if ($type !== 'FX Deal')
                            <!--th>Action</th-->
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        {{ $transactions->isEmpty() ? '<tr><td colspan="6" align="center">- No Records to Display -</td></tr>' : '' }}
                        @foreach ($transactions as $tran)
                            <tr>
                                <td>
                                    {{ date_word($tran->created_at) }}
                                </td>
                                <td>{{ link_to(current_url() . '?uid='.$tran->uid, pretty_str($tran->user->full_name)) }}</td>
                                <td>{{ link_to('/admin/transaction/detail/'.$tran->id, $tran->receipt_id) }}</td>
                                <td>
                                    <?php
                                    switch ($type) 
                                    {
                                        case 'Deposit':
                                            echo $tran->bank ? $tran->bank[0]['bank'] : '';
                                            break;
                                        
                                        case 'FX Deal':
                                            echo sprintf('%s %s/%s', $tran->rate['lock_rate'], $tran->rate['lock_currency_have'], $tran->rate['lock_currency_want']);
                                            break;

                                        case 'Withdraw':
                                            echo $tran->recipient['bank_name'];
                                            break;    
                                        default:
                                            echo $tran->type;
                                            break;
                                    }
                                 ?>
                                </td>
                            @if ($type !== 'FX Deal')
                                <td>
                                    @if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                                        <span class="label label-danger">{{ sprintf('<i class="fa fa-exclamation-triangle"></i> %s', overdueCheck($tran->created_at)) }}</span>
                                    @else
                                        <span class="label label-{{ transactionStatusStyle($tran->status) }}">{{ $tran->status }}</span>
                                    @endif
                                </td>
                            @endif
                                <td class="amount">
                                    @if($tran->type == 'Deposit')
                                        <font class="green">+{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'FX Deal')
                                        <font class="">{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'Withdraw')
                                        <font class="red">-{{ $tran->amountFormat }}</font>
                                    @endif
                                </td>
                                <!--
                                @if ($type !== 'FX Deal')
                                <td class="action-block">
                                     @if (action_check($tran->id, $tran->receipt_id))
                                         @if (trans_perm($tran->type, 'clear', FALSE) OR trans_perm($tran->type, 'voided', FALSE) OR trans_perm($tran->type, 'cancel', FALSE))
                                         <div class="btn-group actions">
                                             <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                 <i class="fa fa-caret-down f14p"></i>
                                             </button>
                                             <ul class="dropdown-menu">
                                                 @if (trans_perm($tran->type, 'clear', FALSE))
                                                 <li>
                                                     <a href="/admin/transaction/clear/{{ $tran->id }}">Clear</a>
                                                 </li>
                                                 @endif
 
                                                @if (trans_perm($tran->type, 'voided', FALSE))
                                                 <li>
                                                     <a href="/admin/transaction/cancel/Voided/{{ $tran->id }}">Void</a>
                                                 </li>
                                                 @endif
 
                                                @if (trans_perm($tran->type, 'cancel', FALSE))
                                                <li>
                                                    <a href="/admin/transaction/cancel/Canceled/{{ $tran->id }}">Cancel</a>
                                                 </li>
                                                 @endif                                         
                                             </ul>
                                         </div>
                                         @endif 
                                     @endif
                                </td>
                                @endif
                                -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- show more button ? -->
                @if ( ! $transactions->isEmpty() AND $total_count > 50)
                <button class="btn btn-default btn-block btn-gry" id="btn_show_more_tx">
                    <input type="hidden" id="request_url" value="{{ sprintf('%s%s', url('admin/transaction/json'), $type ? '/' . $type : '/all') }}">
                    <input type="hidden" id="request_param" value="{{ http_build_query($args) }}">
                    <span class='pull-left ml10' id='btn_show_more_tx_content'>1 - {{ count($transactions) }} record(s), total {{ $total_count }} record(s)</span>
                    <span class='pull-right mr10'>Show more transactions<i class="fa fa-arrow-circle-down lightblue ml10"></i></span>
                </button>
                @endif
            </div>
        </div>
    </div>
@stop

@section('inline-css')
{{ HTML::style('assets/jquery-ui/jquery-ui.min.css') }}
@stop

@section('inline-js')
{{ HTML::script('assets/jquery-ui/jquery-ui.min.js') }}
{{ HTML::script('assets/js/lib/admin/search.js') }}
{{ HTML::script('assets/datatable/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/lib/admin/contents-list.js') }}
<script>
    $(function() {
        $( ".datepicker" ).datepicker();
    });
</script>
@stop