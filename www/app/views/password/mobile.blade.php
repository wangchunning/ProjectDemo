@section('title')
忘记密码
@stop

@section('body-tag')
<body class="password">
@stop

@section('content')
<div class="container">
    <h2 class="form-title">无法登陆？</h2>
    <div class="password-body">
        <div class="box">
            <div class="row">
                <div class="col-sm-12">
                    {{ Form::open(array('url' => 'password/sms-sent', 'role' => 'form', 'class' => 'password-form white-box-body')) }}
                        <div class="row">
                            <div class="pull-right lock pos-rel">
                                <a href="javascript:;" class="underline">安全</a>
                                <span class="fa fa-lock"></span>
                                <div class="tips">
                                    <p class="bold">Anying 换汇安全和隐私保护</p>
                                    <p>Anying 如何保证安全交易？</p>
                                    <p>
                                        我们采用业界领先的技术保证您的财务信息安全
                                    </p>
                                </div>
                            </div>
                            <p class="text-muted pull-left">
                                请提供下面信息，我们会帮助您重新登陆到您的账户
                                 <br> 
                                请选择重置密码的方式，邮件或短信
                            </p>
                        </div>

                        <div class="pt10 reset">
                            <div>
                                <p class="f16p lightblue">手机短信验证</p>
                                <div class="pos-rel form-group {{ $errors->first('pin', 'has-error') }}">
                                    <input type="text" class="form-control has-tip" name="pin" id="pin" placeholder="输入验证码">
                                    <p class="color-f60">* 为了确保您的信息安全，请获取验证码后才能继续操作</p>
                                </div>
                            </div>
                            @if ($errors->has())
                            <p class="text-danger">
                                {{ $errors->first() }}
                            </p>
                            @elseif (Session::has('error'))
                            <p class="text-danger">
                                {{Session::get('msg')}}
                            </p>
                            @endif 
                            <button type="submit" class="btn btn-primary form-lg">重置密码</button>
                        </div>
                        
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('inline-js')
<script type="text/javascript">
    $(function(){
        // show security tips
        $('.lock').hover(function(){
            $('.lock .tips').show();
        }, function(){
            $('.lock .tips').hide();
        });

        // show input tips
        $('.has-tip').focus(function(){
            $(this).next('p').fadeIn('1000');
        });     
        $('.has-tip').blur(function(){
            $(this).next('p').fadeOut('1000');
        });
    })
</script>
@stop
