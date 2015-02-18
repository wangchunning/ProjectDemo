@section('title')
忘记密码
@stop

@section('body-tag')
<body class="password">
@stop

@section('content')
<div class="container">
    <h2 class="form-title">无法登陆?</h2>
    <div class="password-body">
        <div class="box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="password-form white-box-body">
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
                            <div class="by-email">
                                <p class="f16p pt20">创建新密码</p>
                                <p class="color-666 f13p">我们给您发送一封邮件 <a class="underline">{{$email->value}}</a>。点击邮件中的链接就可以重置密码.</p>
                                <p class="tcenter pt20"><a href="{{$email->url}}" target="_blank" type="button" class="btn btn-primary form-lg">验证 Email</a></p>
                                <p class="f16p pt20">还没收到 Email？</p>
                                <ul class="color-666 f13p no-email">
                                    <li>请检查您邮箱的垃圾邮件</li>
                                    <li>我们可以 <a class="underline" href="{{url('password/index', array('email'))}}">重发邮件</a></li>
                                    <li>如果您仍无法收到邮件，我们建议您采用 <a class="underline" href="{{url('password/index', array('mobile'))}}">手机短信</a> 来重置密码</li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
    })
</script>
@stop
