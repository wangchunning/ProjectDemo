@section('title')
登录注册
@stop

@section('body-tag')
<body class="login">
@stop

@section('content')
<div class="container">
    <div class="all-radius white-box-body flat-panel" id="lock-rate-now-pannel">
        <h4>锁定汇率</h4>
        <div class="pannel-body"> 
            <table class="table lock-rate-detail">
                <tbody>
                    <tr>
                        <td width="25%"><span class="desc">汇率:</span></td>                               
                        <td width="50%" class="detail f18p">{{ sprintf('%s/%s <span class="amount-desc lock-rate-label">%s</span>', substr(Session::get('lock_currency'), 0, 3), substr(Session::get('lock_currency'), 3), Session::get('lock_rate')) }} </td>
                        <td width="25%" align="center" rowspan="2">
                            <div id="countdown-pannel">
                                <div id="countdown_dashboard">
                                    <div class="dash minutes_dash">
                                        <div class="digit">0</div>
                                        <div class="digit">0</div>
                                    </div>
                                    <div class="colon">:</div>
                                    <div class="dash seconds_dash">
                                        <div class="digit">0</div>
                                        <div class="digit">0</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="desc">您的交易:</span></td>                               
                        <td class="detail color-666 f16p">{{ sprintf('<span class="color-333">%s</span> %s 兑换 <span class="amount-desc" id="amount-want-label">%s</span> %s', Exchange::read('lock_amount'), currencyName(substr(Session::get('lock_currency'), 0, 3)), Exchange::read('lock_amount') * Exchange::read('lock_rate'), currencyName(substr(Session::get('lock_currency'), 3))) }} </td>
                    </tr>
                </tbody>
            </table>
            <p class="notice color-666">
                <span class="bold">提示：</span>锁定汇率的有效时间为 5 分钟，请您尽快登录或注册新的账号完成该笔交易
            </p>
        </div>
    </div>
    <!-- login -->
    <div class="login-body mt20">
        <div class="login-box">
            <div class="row">
                <div class="col-sm-6">
                    <!-- login form -->
                    {{ Form::open(array('url' => 'login', 'role' => 'form', 'class' => 'login-form white-box-body', 'id' => 'login-form')) }}
                        <h3 class="deepred">登录</h3>

                        @if ($errors->has())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                        @endif

                        @if (Session::has('error'))
                        <div class="alert alert-danger">
                            您的 Email 或密码不正确
                        </div>
                        @endif

                        <div class="form-group {{ $errors->first('email', 'has-error') }}">
                            <label for="email">Email</label>
                            <input type="email" class="form-control validate[required,custom[email]]" value="{{ Input::old('email') }}" name="email" id="email" placeholder="Enter email">
                        </div>
                        <div class="form-group clearfix {{ $errors->first('password', 'has-error') }}">
                            <label for="password">密码</label>
                            <input type="password" class="form-control mb10 validate[required]" name="password" id="password" placeholder="Password">
                            <div class="radio-inline">
                                <input type="radio" name="login_type" value="personal" checked>
                                <abbr title="个人/公司账户的所有者">个人/公司账户</abbr>
                            </div>
                            <div class="radio-inline">
                                <input type="radio" name="login_type" value="business_member">
                                <abbr title="被邀请管理公司账户">公司成员账户</abbr>
                            </div>
                            <div class="help-block mb6">
                                <span id="forgot-password"><a class="underline" href="{{ url('password') }}">找回密码</a></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary login-lg">登录</button>
                    {{ Form::close() }}
                    <!-- /login form -->
                </div>
                <div class="col-sm-6 select-role">
                    <div class="col-sm-12 login-form white-box-body">
                        <div class="row">
                            <div class="col-sm-7">
                                <h4>个人账户</h4>
                                <p class="color-666">
                                    注册为个人账户，换汇用于旅游、购物 ...... 等
                                </p>
                            </div>
                            <div class="col-sm-5 start">
                                <a href="{{ url('register/personal') }}" class="btn btn-primary">开始注册</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 login-form white-box-body">
                        <div class="row">
                            <div class="col-sm-7">
                                <h4>公司账户</h4>
                                <p class="color-666">
                                    注册为公司账户，换汇用于在线交易、国际贸易等
                                </p>
                            </div>
                            <div class="col-sm-5 start">
                                <a href="/register/business" class="btn btn-primary">开始注册</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /login -->
    <div class="modal fade" id="refresh-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background-color:#fefbed;">
                    <h5>Warning!</h5>
                    <p>We are sorry about that your lock rate is Expired. You should refresh the rate again, we will convert the rate again.</p>
                    <p class="mt20">Do you want to refresh now?</p>
                    <p class="mt20">
                        <a class="btn btn-primary" href="javascript:;" id="refresh-rate">Refresh Rate Now</a>
                        or
                        {{ link_to('/', 'Cancel') }}
                    </p>
                </div>
            </div>
        </div>
    </div> 
</div>
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/lock-rate-common.js')}}
{{ HTML::script('assets/js/jquery.lwtCountdown-1.0.js') }}
{{ HTML::script('assets/js/lib/lock-rate-money.js')}}
<script>
var mins = {{ intval((Exchange::read('lock_time') - Carbon::now()->timestamp) / 60) }}; 
var secs = {{ (Exchange::read('lock_time') - Carbon::now()->timestamp) % 60}};
$(function(){
    $("#login-form").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
