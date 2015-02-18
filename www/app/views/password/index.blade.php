@section('title')
忘记密码
@stop

@section('body-tag')
<body class="password">
@stop

@section('content')
<div class="container">
    <div class="password-body">
        <div class="box">
            <div class="row">
                <div class="col-sm-12">
                    {{ Form::open(array('url' => 'password', 'role' => 'form', 'class' => 'password-form white-box-body')) }}
                        <input type="hidden" id="reset_by" name="reset_by" value="{{$reset_by}}">
                        <div class="row">
                            <div class="pull-right lock pos-rel mr10">
                                <a href="javascript:;">安全</a>
                                <span class="fa fa-lock"></span>
                                <div class="tips">
                                    <p class="bold">Anying 换汇安全和隐私保护</p>
                                    <p>Anying 如何保证安全交易？</p>
                                    <p>
                                       我们采用业界领先的技术保证您的财务信息安全
                                    </p>
                                </div>
                            </div>
                            <h2 class="ml15 mt0">无法登陆？</h2>
                            <p class="text-muted pull-left ml15">
                                请提供下面信息，我们会帮助您重新登陆到您的账户
                                 <br> 
                                请选择重置密码的方式，Email 或手机短信
                            </p>
                        </div>

                        <div class="pt10 reset">
                            <ul class="nav nav-tabs mb20">
                                <li id="by-email">
                                    <a href="javascript:;">Email</a>
                                </li>
                                <li id="by-mobile">
                                    <a href="javascript:;">手机短信</a>
                                </li>
                            </ul>

                            <div class="by-email">
                                <p class="f16p lightblue">请输入您的 Email 地址</p>
                                <div class="pos-rel form-group {{ $errors->first('email', 'has-error') }}">
                                    <input type="email" class="form-control has-tip" value="{{ Input::old('email') }}" name="email" id="email" placeholder="输入 Email">
                                    <p class="color-f60">* 请输入您的注册邮箱</p>
                                </div>
                                <div class="captcha">
                                    {{HTML::image(Captcha::img(), 'Captcha image')}}
                                    <p class="show-newcode"><span class="fa fa-refresh color-link"></span> <a href="javascript:;" class="underline">换一张</a></p>
                                </div>
                                <p class="f16p lightblue pt10">输入上面字母</p>
                                <div class="form-group {{ $errors->first('captcha', 'has-error') }}">
                                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="输入上面字母">
                                </div>
                            </div>
                            <div class="by-mobile">
                                <p class="f16p lightblue">请输入您的手机号</p>
                                <div class="pos-rel form-group {{ $errors->first('phone_number', 'has-error') }}">
                                    <input type="tel" class="form-control has-tip" value="{{ Input::old('phone_number') }}" name="phone_number" id="phone_number" placeholder="请输入您的手机号">
                                    <p class="color-f60">* 请输入有效的手机号码，我们会发送验证码到您的手机</p>
                                </div>
                            </div>
                            @if ($errors->has())
                            <p class="text-danger">
                                {{ $errors->first() }}
                            </p>
                            @elseif (Session::has('error'))
                            <p class="text-danger">
                                @if ($reset_by == 'email')
                                    @if (Session::has('captcha_e'))
                                    验证码不正确!
                                    @else
                                    我们无法找到与该 Email 关联的 Anying 账号
                                    @endif
                                @else
                                我们无法找到与该手机号关联的 Anying 账号
                                @endif
                            </p>
                            @endif 
                            <button type="submit" class="btn btn-primary form-lg">获取安全链接</button>
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
        var base_url = '{{url('/')}}';
        var reset = $('#reset_by').val();
        check_resetby(reset);

        $('#by-email,#by-mobile').on('click', function(){
            reset = $(this).attr('id') == 'by-email' ? 'email' : 'mobile';
            check_resetby(reset);
        });

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

        // 点击刷新验证码
        $('.captcha img,.captcha .show-newcode').on('click', function(){
            console.log('clock');
            var rand_num = parseInt(Math.random()*800000 + 100000);
            $('.captcha img').attr('src', base_url + '/captcha?' + rand_num);
        });

        function check_resetby(reset_by) {
            if (reset_by == 'email') {
                $('#reset_by').val('email');
                $('#by-mobile').removeClass('active');
                $('#by-email').addClass('active');
                $('.by-mobile').hide();
                $('.by-email').show();
                $('.form-lg').text('发送邮件重置密码');
            }else{
                $('#reset_by').val('phone_number');
                $('#by-email').removeClass('active');
                $('#by-mobile').addClass('active');
                $('.by-email').hide();
                $('.by-mobile').show();
                $('.form-lg').text('获取短信验证码');
            }
        }
    })
</script>
@stop
