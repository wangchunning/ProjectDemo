@section('title')
锁定汇率
@stop

@section('content')
    @include('lockrate.subnav')
    {{ Form::open(array('url' => 'fx/confirm', 'role' => 'form', 'class' => 'form-horizontal') )}}
    <div class="container">
        <div class="dashboard-body">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
            <div>
                <div class="row ml0 mr0">
                    <div class="col-sm-9">
                        <h2 class="form-title">Confirm FX Detail</h2>
                    </div>
                    <div class="col-sm-2">
                        <span class="label label-primary f16p">Balance：{{ sprintf('%s %s', $balance, Exchange::read('lock_currency_have')) }} </span>
                    </div>
                </div>          
                <div class="alert alert-warning"><strong>* Notes:</strong> You MUST confirm the requirement in 5 munites. If you want to change currency,the lock time will be restrart.</div>
            </div>
            <div class="panel panel-primary" id="lock-rate-now-pannel">        
                <div class="panel-heading">
                    <h5>Lock rate</h5>
                </div>            
                <div class="pannel-body"> 
                    <h6>Currency Spot Rate</h6>
                    <p>1 {{ currencyName(substr(Session::get('lock_currency'), 0, 3)) }} equals <span class="lock-rate-label">{{ Session::get('lock_rate') }}</span> {{ currencyName(substr(Session::get('lock_currency'), 3)) }}</p>
                    <p>
                        {{ currencyFlag(substr(Session::get('lock_currency'), 0, 3)) }} 
                        <span class="amount-label">{{ Exchange::read('lock_amount') }}</span>
                        {{ substr(Session::get('lock_currency'), 0, 3) }} - 
                        {{ currencyName(substr(Session::get('lock_currency'), 0, 3)) }}
                        <span class="equal-label"> = </span>
                        {{ currencyFlag(substr(Session::get('lock_currency'), 3)) }} 
                         <span class="amount-label" id="amount-want-label">{{ Exchange::read('lock_amount') * Exchange::read('lock_rate') }}</span>
                        {{ substr(Session::get('lock_currency'), 3) }} - 
                        {{ currencyName(substr(Session::get('lock_currency'), 3)) }}                        
                    </p>
                    <div id="countdown-pannel">
                        <div id="start-label"><span class="fa fa-clock-o"></span> Start：<span id="lock-time-label">{{ time_to_tz(Exchange::read('lock_time') - 5 * 60) }}</span></div>
                        <div id="countdown_dashboard">
                            <div class="dash minutes_dash">
                                <div class="digit">0</div>
                                <div class="digit">0</div>
                            </div>
                            <div style="display:inline-block;float:left;margin-right:5px;color:#FF6600">:</div>
                            <div class="dash seconds_dash">
                                <div class="digit">0</div>
                                <div class="digit">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h4>Exchange Details</h4>
                <p>You are transferring the {{ sprintf('%s %s', Exchange::read('lock_amount'), Exchange::read('lock_currency_have')) }}</p>
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
                            <td>{{ sprintf('%s %s', Exchange::read('lock_amount'), substr(Exchange::read('lock_currency'), 0, 3)) }}</td>
                            <td>{{ sprintf('1 %s = %s %s', substr(Exchange::read('lock_currency'), 0, 3), Exchange::read('lock_rate'), substr(Exchange::read('lock_currency'), 3)) }}</td>
                            <td>{{ sprintf('%s %s', Exchange::read('lock_amount') * Exchange::read('lock_rate'), substr(Exchange::read('lock_currency'), 3)) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <p class="text-right"><strong>Your Total Recharge</strong></p>
                                <p class="text-right">{{ sprintf('%s %s', Exchange::read('lock_amount') * Exchange::read('lock_rate') , substr(Exchange::read('lock_currency'), 3)) }}</p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
            </div>            
            <div class="text-center">
                <input type="hidden" name="payment_method" value="balance">
                <button type="submit" class="btn btn-primary btn-lg">Confirm the Deal</button> Or, {{ link_to('fx', 'Cancel') }}                                            
            </div>
        </div>
    </div> 
    {{ Form::close() }}
    <div class="modal fade" id="refresh-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background-color:#fefbed;">
                    <h5>Warning!</h5>
                    <p>We are sorry about that your lock rate is Expired. You should refresh the rate again, we will convert the rate again.</p>
                    <p class="mt20">Do you want to refresh now?</p>
                    <p class="mt20">
                        <a class="btn btn-primary" href="javascript:;" id="refresh-rate-btn">Refresh Rate Now</a>
                        or
                        {{ link_to('lockrate', 'Cancel') }}
                    </p>
                </div>
            </div>
        </div>
    </div>   
@stop

@section('inline-js')
{{ HTML::script('assets/js/jquery.lwtCountdown-1.0.js') }}
{{ HTML::script('assets/js/lib/lock-rate-money.js')}}
<script>
var mins = {{ intval((Exchange::read('lock_time') - Carbon::now()->timestamp) / 60) }}; 
var secs = {{ (Exchange::read('lock_time') - Carbon::now()->timestamp) % 60}};
</script>

@stop