@section('title')
锁定汇率
@stop

@section('content')
    @include('lockrate.subnav')
    <div class="container">

        @if (real_user()->profile->status != 'verified')
        <div class="mt20 alert alert-danger  alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            为了能够更加安全的交易，在您可以换汇之前，请先验证您的 Email，绑定手机，并提供身份验证
            
        </div>
        @endif        
        <div class="dashboard-body">
            @if ($errors->has() OR Session::has('error'))
            <div class="alert alert-danger">
                {{ $errors->has() ? $errors->first() : Session::get('error') }}
            </div>
            @endif             
            @include('lockrate.quote')
            <hr>
            <div>
                <h4 class="deepred">您还可以进行哪些操作?</h4>
                <div class="row">
                    <div class="col-sm-6">                
                        <div class="media">
                          <a class="pull-left" href="/fund">
                            <img src="/assets/img/addfund.png" width="80px">
                          </a>
                          <div class="media-body pointer" onclick="location.href='/fund'">
                            <h5 class="media-heading deepred">存款</h5>
                            <p class="color-666">
                            	您可以将资金从银行账户转到您的 Anying 账户，我们支持电汇、信用卡等方式方便您的转账
                         
                            </p>
                          </div>
                        </div>   
                    </div>
                    <div class="col-sm-6">
                        <div class="media">
                          <a class="pull-left" href="/withdraw">
                            <img src="/assets/img/withdraw.png" width="80px">
                          </a>
                          <div class="media-body pointer" onclick="location.href='/withdraw'">
                            <h5 class="media-heading deepred">取款</h5>
                            <p class="color-666">
                            	您可以将您在 Anying 账户的余额转到您或其他人的银行账户</p>
                          </div>
                        </div>                          
                    </div>        
                </div>  
                <hr>   
            </div>

            <div>
                <h4 class="deepred">国际汇款的手续费是多少？</h4>
                <p class="color-666">
                	我们会根据您的收款行或汇款方式收取少许金额的手续费，具体金额会在您做每笔交易时自动计算
                </p>
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
    {{ HTML::script('assets/js/lib/home.js') }}     
@stop