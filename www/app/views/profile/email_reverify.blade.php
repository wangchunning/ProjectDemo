@section('title')
重新验证 Email
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
            <div id="handingMsg"></div>
            {{ Form::open(array('url' => 'profile/email-verify', 'role' => 'form', 'id' => 'emailFrm')) }}
                <h2><i class="fa fa-envelope"></i>  验证 Email </h2>
                <hr class="mt0">
                <h3>什么是 Email 验证?</h3>
                <p class="help-block">
                	为了您的交易及信息安全，我们需要验证您 Email 的真实性。我们会发送给您一封 Email，点击其中的验证链接即可完成验证。
                	如果您没有收到 Email，请查看您的垃圾邮件
                	
                </p>
                <h3>Email 验证</h3>
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control validate[required,custom[email]]" id="email" placeholder="如 me@wexchange.com" value="{{ Input::old('email', $user->email) }}">
                    </div>
                </div>
                <hr>                
                <button type="submit" class="btn btn-primary btn-lg">重新发送验证邮件</button> 或, <a href="javascript:;" onclick="javascript:history.back(-1);">取消</a>                            
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-js')
    {{ HTML::script('assets/js/lib/profile.js')}}
    <script>
    $(function(){
        profile.init();
        $("#emailFrm").validationEngine({
            ajaxFormValidation : true,
            ajaxFormValidationMethod : "post",
            promptPosition: "inline",
            maxErrorsPerField: 1,
            addFailureCssClassToField: "has-error",
            onBeforeAjaxFormValidation : function(){
                $.fancybox.showLoading();
            },
            onAjaxFormComplete : function(status, form, json){
                $.fancybox.hideLoading();
                if (json.status == 'error') {
                    $('#handingMsg').html('<div class="alert alert-danger">' + json.data.msg + '</div>');
                }else{
                    window.location.href='/home';
                };             
            }
        });
    })
    </script>    
@stop