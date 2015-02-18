@section('title')
Rate
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="dashboard-body flat-panel">
            @if (check_perm('view_rate', FALSE))
            <div id="rate-pannel">
                <h2 class="mb40"><i class="fa fa-money"></i> Rate Management</h2>                        
                
                <ul class="nav nav-tabs" id="nav-vip">
                    <li class="active"><a href="#basic" data-toggle="pill">Basic</a></li>
                    <li><a href="#silver" data-toggle="pill">Silver</a></li>
                    <li><a href="#golden" data-toggle="pill">Golden</a></li>
                    <li><a href="#platinum" data-toggle="pill">Platinum</a></li>
                    <li class="pull-right">
                    @if (check_perms('edit_rate_margin,edit_vip_margin', 'OR', FALSE))
                        <span class="btn btn-default pull-right f16p" id="edit-margin-btn">Edit Margin</span>
                        <span class="pull-right" id="action-margin-panel">
                            <a href="#" class="btn btn-primary js-save-margin">Save</a>
                            <a href="#" class="btn btn-default ml15 js-cancel-margin">Cancel</a>                       
                        </span>
                    @endif
                    </li>
                </ul>
                <input type="hidden" id="rate-stop" value="0">
                <div class="tab-content table-responsive rate">  
                    <div class="tab-pane active" id="basic">
                        <div class="row mt10 ml15">
                            <p class="grey f14p">Basic Transaction Fee</p>
                            <p class="f18p">$<span class="tx-fee">{{ $tx_fee['amount'] }}</span> {{ $tx_fee['currency'] }} </p>
                        </div>
                        <hr class="border3">
                        <table class="table table-hover rate-table">
                            <thead>
                                <tr>
                                    <th colspan="4"></th>
                                    <th colspan="2">Market Rate</th>
                                    <th colspan="2">Wexchange Rate</th>
                                    <th>Time</th>
                                </tr>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>Market Bid</th>
                                    <th>Market Ask</th>
                                    <th>Bid</th>
                                    <th>Ask</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rates as $rate)
                                <tr class="{{ $rate->from . $rate->to }}" data-id="{{ $rate->from . $rate->to }}">
                                    <td>
                                        @if (check_perm('remove_currency', FALSE))
                                        <a href="javascript:;" title="Remove" class="remove-btn fa fa-minus-circle"></a>
                                        @endif
                                    </td>
                                    <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                                    <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                                    <td width="50" class="rate_change"></td>
                                    <td class="market-bid"><img src="/assets/img/spinner.gif"></td>
                                    <td class="market-ask"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-bid{{ check_perm('edit_rate_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-ask{{ check_perm('edit_rate_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="time"><img src="/assets/img/spinner.gif"></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                @if (check_perm('add_rate', FALSE))
                                <tr class="rate-add-action">
                                    <td colspan="11">
                                        <a href="javascript:;" id="add-rate-btn">+Add New Rate</a>
                                        <div id="add-rate-pannel">
                                            Add A New Exchange Currency：
                                            <div class="row mt20">
                                                <div class="col-sm-3 col-sm-offset-1">
                                                    {{ allCurrenciesSelecter('currency_from') }}
                                                </div>
                                                <div class="col-sm-1">
                                                    <span class="equal-label">=</span>
                                                </div>
                                                <div class="col-sm-3">
                                                    {{ allCurrenciesSelecter('currency_to') }}                                    
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="javascript:;" class="btn btn-primary" id="save-rate-btn">Save</a>
                                                    <a href="javascript:;" class="btn btn-default" id="cancel-btn">Cancel</a>                                                                                                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if (check_perms('edit_rate_margin,edit_vip_margin', 'OR', FALSE))
                                <tr class="rate-edit-action">
                                    <td colspan="11">
                                        <a href="javascript:;" class="btn btn-primary js-save-margin">Save</a>
                                        <a href="javascript:;" class="btn btn-default ml15 js-cancel-margin">Cancel</a>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div> <!-- /basic -->
                    <div class="tab-pane" id="silver">
                        <div class="row mt10 ml15">
                            <p class="grey f14p">Silver Transaction Fee</p>
                            <p class="f18p">$<span class="tx-fee">{{ $tx_fee['silver_amount'] }}</span> {{ $tx_fee['currency'] }} </p>
                        </div>
                        <hr class="border3">
                        <table class="table table-hover rate-table">
                            <thead>
                                <tr>
                                    <th colspan="4"></th>
                                    <th colspan="2">Market Rate</th>
                                    <th colspan="2">VIP Rate</th>
                                    <th>Time</th>
                                </tr>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>Market Bid</th>
                                    <th>Market Ask</th>
                                    <th>Silver Bid</th>
                                    <th>Silver Ask</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rates as $rate)
                                <tr class="{{ $rate->from . $rate->to }}" data-id="{{ $rate->from . $rate->to }}">
                                    <td>
                                        @if (check_perm('remove_currency', FALSE))
                                        <a href="javascript:;" title="Remove" class="remove-btn fa fa-minus-circle"></a>
                                        @endif
                                    </td>
                                    <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                                    <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                                    <td width="50" class="rate_change"></td>
                                    <td class="market-bid"><img src="/assets/img/spinner.gif"></td>
                                    <td class="market-ask"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-bid{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-ask{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="time"><img src="/assets/img/spinner.gif"></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                @if (check_perms('edit_rate_margin,edit_vip_margin', 'OR', FALSE))
                                <tr class="rate-edit-action">
                                    <td colspan="11">
                                        <a href="javascript:;" class="btn btn-primary js-save-margin">Save</a>
                                        <a href="javascript:;" class="btn btn-default ml15 js-cancel-margin">Cancel</a>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div> <!-- /silver -->
                    <div class="tab-pane" id="golden">
                        <div class="row mt10 ml15">
                            <p class="grey f14p">Golden Transaction Fee</p>
                            <p class="f18p">$<span class="tx-fee">{{ $tx_fee['golden_amount'] }}</span> {{ $tx_fee['currency'] }} </p>
                        </div>
                        <hr class="border3">
                        <table class="table table-hover rate-table">
                            <thead>
                                <tr>
                                    <th colspan="4"></th>
                                    <th colspan="2">Market Rate</th>
                                    <th colspan="2">VIP Rate</th>
                                    <th>Time</th>
                                </tr>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>Market Bid</th>
                                    <th>Market Ask</th>
                                    <th>Golden Bid</th>
                                    <th>Golden Ask</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rates as $rate)
                                <tr class="{{ $rate->from . $rate->to }}" data-id="{{ $rate->from . $rate->to }}">
                                    <td>
                                        @if (check_perm('remove_currency', FALSE))
                                        <a href="javascript:;" title="Remove" class="remove-btn fa fa-minus-circle"></a>
                                        @endif
                                    </td>
                                    <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                                    <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                                    <td width="50" class="rate_change"></td>
                                    <td class="market-bid"><img src="/assets/img/spinner.gif"></td>
                                    <td class="market-ask"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-bid{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-ask{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="time"><img src="/assets/img/spinner.gif"></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                @if (check_perms('edit_rate_margin,edit_vip_margin', 'OR', FALSE))
                                <tr class="rate-edit-action">
                                    <td colspan="11">
                                        <a href="javascript:;" class="btn btn-primary js-save-margin">Save</a>
                                        <a href="javascript:;" class="btn btn-default ml15 js-cancel-margin">Cancel</a>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>                    
                    </div><!-- /golden -->
                    <div class="tab-pane" id="platinum">
                        <div class="row mt10 ml15">
                            <p class="grey f14p">Platinum Transaction Fee</p>
                            <p class="f18p">$<span class="tx-fee">{{ $tx_fee['platinum_amount'] }}</span> {{ $tx_fee['currency'] }} </p>
                        </div>
                        <hr class="border3">
                        <table class="table table-hover rate-table">
                            <thead>
                                <tr>
                                    <th colspan="4"></th>
                                    <th colspan="2">Market Rate</th>
                                    <th colspan="2">VIP Rate</th>
                                    <th>Time</th>
                                </tr>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>Market Bid</th>
                                    <th>Market Ask</th>
                                    <th>Platinum Bid</th>
                                    <th>Platinum Ask</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rates as $rate)
                                <tr class="{{ $rate->from . $rate->to }}" data-id="{{ $rate->from . $rate->to }}">
                                    <td>
                                        @if (check_perm('remove_currency', FALSE))
                                        <a href="javascript:;" title="Remove" class="remove-btn fa fa-minus-circle"></a>
                                        @endif
                                    </td>
                                    <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                                    <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                                    <td width="50" class="rate_change"></td>
                                    <td class="market-bid"><img src="/assets/img/spinner.gif"></td>
                                    <td class="market-ask"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-bid{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="vip-ask{{ check_perm('edit_vip_margin', FALSE) ? '' : ' no-edit' }}"><img src="/assets/img/spinner.gif"></td>
                                    <td class="time"><img src="/assets/img/spinner.gif"></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                @if (check_perms('edit_rate_margin,edit_vip_margin', 'OR', FALSE))
                                <tr class="rate-edit-action">
                                    <td colspan="11">
                                        <a href="javascript:;" class="btn btn-primary js-save-margin">Save</a>
                                        <a href="javascript:;" class="btn btn-default ml15 js-cancel-margin">Cancel</a>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>                    
                    </div><!-- /platinum -->                                                
                </div><!-- /tab-content -->
            </div>
            @endif
            @if (check_perm('view_basic_rate', FALSE))
            <div id="basic-rate-pannel">        
                <h2>Basic Rate</h2>
                <hr>
                <div class="pannel-body table-responsive">            
                    <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                @if (check_perm('edit_basic_rate',FALSE))
                                <a href="javascript:;" class="btn btn-primary" id="update-basic-rate-btn">Update Basic Rate</a>
                                @endif
                            </th>
                            <th colspan="4">
                                <div id="basic-rate-tip">Routinely update daily, minimize the risk between CNY & AUD</div>
                                @if (check_perm('edit_basic_rate',FALSE))
                                <div id="basic-rate-input">
                                    <div class="inline usdcny-label">1 {{ currencyFlag('USD') }} USD <span class="equal-label">=</span> <input type="text" id="usdcny_rate" name="usdcny_rate"> {{ currencyFlag('CNY') }}CNY</div>
                                    <div class="inline"><a href="javascript:;" class="btn btn-primary" id="save-basic-rate-btn">Update</a> <a href="javascript:;" class="btn btn-default" id="basic-rate-cancel-btn">Cancel</a></div>
                                    <div class="inline color-error" id="form-error-tip"></div>
                                </div>
                                @endif
                            </th>
                        </tr>   
                    </thead>
                    <tbody>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="6"><a href="javascript:;" id="more-btn">More History...</a></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@stop

@section('inline-js')
<script>
var tickList = "{{ currencyString() }}";
var margins = $.parseJSON('{{ rateMarginJson() }}');

$('#nav-vip a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})

</script>
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('assets/js/lib/admin/rate.js') }}
@stop