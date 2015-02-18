@section('title')
FX
@stop

@section('content')
    @include('lockrate.subnav')
    <div class="container">
        <div class="dashboard-body">
            <div>
                <h2 class="form-title">Add FX List</h2>
                <p class="help-block">Calculate the amount you’ll get when changing part of or all your balance from one currency to another.
    Convert part of or all your balance into different currencies.</p>
            </div>
            @if ($errors->has() OR Session::has('error'))
            <div class="alert alert-danger">
                {{ $errors->has() ? $errors->first() : Session::get('error') }}
            </div>
            @endif        
            @include('lockrate.quote')          
            <div class="panel panel-primary" id="fx-list-pannel">        
                <div class="panel-heading">
                    <h5>FX List</h5>
                </div>  
                <div class="pannel-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Payment Status</th>
                                <th>Rate</th>
                                <th>Gross</th>
                            </tr>
                        </thead>
                        <tbody>
                        {{ $fxs->isEmpty() ? '<tr><td colspan="5" align="center">- No Records to Display -</td></tr>' : ''}}                            
                        @foreach ($fxs as $fx)
                            <tr>
                                <td>{{ $fx->created_at }}</td>
                                <td>{{ $fx->status }}</td>
                                <td>{{ sprintf('%s %s/%s', $fx->rate['lock_rate'], $fx->rate['lock_currency_have'], $fx->rate['lock_currency_want']) }}</td>
                                <td>{{ sprintf('%s %s', $fx->amount, $fx->currency) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">{{ $fxs->links() }}</td>
                            <tr>
                        </tfoot>                        
                    </table>
                </div>      
            </div>
        </div>
    </div>    
@stop

@section('inline-js')
<!--[if lte IE 8]><script src="/assets/flot/excanvas.min.js"></script><![endif]-->
    {{ HTML::script('assets/flot/jquery.flot.min.js') }}
    {{ HTML::script('assets/flot/jquery.flot.time.min.js') }}
    {{ HTML::script('assets/js/lib/lock-rate.js') }}
    {{ HTML::script('assets/js/lib/rate-chart.js') }}    
@stop