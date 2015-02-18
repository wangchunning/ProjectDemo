@section('title')
Rate
@stop

@section('content')
    @include('lockrate.subnav')
    @include('breadcrumb')
    <div class="container mt20">
        <div class="panel panel-primary" id="rate-pannel">        
            <div class="panel-heading">
                <h5>Foreign Exchange Rates</h5>
            </div>            
            <div class="pannel-body table-responsive rate">  
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                            @if (is_vip($user->profile->vip_type))
                            <th colspan="2">VIP Rate</th>
                            @else
                            <th colspan="2">Wexchange Rate</th>
                            @endif   
                            <th>Time</th>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            @if (is_vip($user->profile->vip_type))
                            <th>VIP Bid</th>
                            <th>VIP Ask</th>
                            @else
                            <th>Bid</th>
                            <th>Ask</th>
                            @endif
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($rates as $rate)
                        <tr class="{{ $rate->from . $rate->to }}">
                            <td>{{ currencyFlag($rate->from) . $rate->from}}</td>
                            <td>{{ currencyFlag($rate->to) . $rate->to }}</td>
                            <td class="bid"><img src="/assets/img/spinner.gif"></td>
                            <td class="ask"><img src="/assets/img/spinner.gif"></td>
                            <td class="time"><img src="/assets/img/spinner.gif"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('inline-js')
<script>
var tickList = "{{ currencyString() }}";
</script>
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('assets/js/lib/customer-rate.js') }}
@stop