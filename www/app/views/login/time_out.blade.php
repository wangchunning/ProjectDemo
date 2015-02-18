@section('title')
超时登出
@stop

@section('body-tag')
<body class="login">
@stop

@section('content')
<div class="container">
    <!-- login -->
    <div class="login-body">
        <div class="login-box">
            <div class="row time-out">
                <div class="col-sm-6">
                    <div class="all-radius white-box-body">
                        <div class="alert alert-warning">
                        <h3><i class="fa fa-exclamation-triangle"></i> 抱歉!</h3>
                        <h4>由于长时间没有活动，您已登出系统</h4>
                        </div>
                         <p>
                         	出于安全考虑，系统将自动登出长时间没有活动的用户
                         </p>
                        <p>如果您还要继续使用，请您再次登录</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- login form -->
                    {{ Form::open(array('url' => 'login', 'role' => 'form', 'class' => 'login-form white-box-body', 'id' => 'login-form')) }}
                        <h3 class="lightblue">登录</h3>

                        @if ($errors->has())
                        <p class="text-danger">
                            {{ $errors->first() }}
                        </p>
                        @endif

                        @if (Session::has('error'))
                        <p class="text-danger">
                            Email 或密码不正确
                        </p>
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
            </div>
        </div>
    </div>
    <!-- /login -->
</div>
@stop

@section('inline-js')
<script>
$(function(){
    $("#login-form").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
