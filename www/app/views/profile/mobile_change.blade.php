@section('title')
修改手机号码 
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
            <h2><i class="fa fa-shield"></i>   手机安全验证</h2>
            <hr class="mt0">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
            <div>
                <h5 class="color-666">什么是 Anying 手机安全验证</h5>
                <p class="color-666">
                	Anying 手机短信验证是非常有效、方便的授权系统。当您的特定行为需要安全验证时，
                	系统会发送给您手机一个验证码，只有验证码匹配才可继续操作
           		</p>
                <h5 class="mt40 color-666">什么时候您需要使用手机安全验证？</h5>
                <p class="color-666">系统会要求您索取验证码，之后系统会将验证码发送到您的手机，而后您输入正确的验证码：
                    <br>a. 换汇交易时取款到新的收款人
                    <br>b. 添加收款人到您的地址簿</p>
                <h5 class="mt40 color-666">Anying 手机安全验证如何保证安全？</h5>
                <p class="color-666">
                	传统的授权方式取决于你所知道的事情，例如密码，安全提示问题等。在此之上，我们利用您所拥有的东西进行验证，例如手机。我们将安全码发送到您的手机上，这样可以防止盗取、伪造交易的风险</p>
                <hr>
            </div>
            {{ Form::open(array('url' => 'profile/security', 'role' => 'form', 'id' => 'profileVerifyFrm')) }}
                <div id="handingMsg"></div>
                <div class="form-group profile-security row">
                    <h5 class="col-sm-12 mt0 color-666">手机安全验证需要您提供</h5>
                    <div class="col-sm-3">                
                        <label for="phone_code"><b>国家码</b></label>
                        {{ callingCodeSelecter('phone_code', ($user->verify->security_verified AND $user->verify->phone_code) ? $user->verify->phone_code : $user->reg_country) }}
                    </div>
                    <div class="col-sm-3">                
                        <label for="mobile"><b>手机号</b></label>
                        <input type="text" name="mobile" class="form-control" id="mobile" value="{{ Input::old('mobile') }}">
                    </div>    
                    <div class="col-sm-2">
                        <a href="javascript:;" id="getPinBtn" class="btn btn-primary mt25">获取验证码</a>
                    </div>                
                </div>
                <div class="form-group row{{ $errors->has() ? '' : ' hide' }}" id="pinField">
                    <div class="col-sm-2">                
                        <label for="pin">输入验证码</label>
                        <input type="text" name="pin" class="form-control" id="pin" value="{{ Input::old('pin') }}">
                    </div> 
                </div>     
                <hr>
                <button type="submit" class="btn btn-primary btn-lg">保存</button> 或, <a href="javascript:;" onclick="javascript:history.back(-1);">取消</a>                                    
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-css')
{{ HTML::style('assets/css/selectize.bootstrap3.css') }}
@stop

@section('inline-js')
    {{ HTML::script('assets/js/selectize.js') }}
    {{ HTML::script('assets/js/lib/profile.js')}}
    <script>
    $(function(){
        security.init();
        $('#phone_code').selectize();
    })
    </script>
@stop