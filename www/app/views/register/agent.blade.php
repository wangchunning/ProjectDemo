@section('title')
客户经理账户注册
@stop

@section('body-tag')
<body class="register">
@stop

@section('content')

<div class="container flat-panel">
    <div class="white-box-body all-radius">

        <h2><i class="fa fa-user"></i>输入您的信息<small class="pull-right">* 所有信息都是必填项</small></h2>
        <hr class="mt0">
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif            

        {{ Form::open(array('url' => 'register/agent', 'role' => 'form', 'id' => 'agentFrm')) }}

        <h3>登录</h3>
        <span class="help-block">您将使用 Email 作为登录用户名</span>
        <div class="form-group row">
            <div class="col-sm-5">
                <label for="email">Email</label>
                <input type="email" name="email" autocomplete="off" class="form-control validate[required,custom[email],ajax[ajaxEmailCall]]" id="email" placeholder="me@tiantianfortune.com" value="{{ Input::old('email') }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">                
                <label for="password">密码</label>
                <input type="password" name="password" autocomplete="off"  class="form-control validate[required,minSize[8]]" id="password" placeholder="密码，至少8位">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">                
                <label for="repassword" class="sr-only">确认密码</label>                    
                <input type="password" name="password_confirmation" class="form-control validate[required,minSize[8],equals[password]]" id="repassword" placeholder="确认密码">
            </div>
        </div>

        <hr>

        <h3>个人信息</h3>
        <span class="help-block">请输入您的姓名，与身份证件上的信息一致</span>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="name">姓名</label>
                <input type="text" name="name" class="form-control validate[required]" id="name" value="{{ Input::old('name') }}">
            </div>
            <div class="col-sm-3">
                <label for="id_number">身份证号</label>
                <input type="text" name="id_number" placeholder=""
                    class="form-control validate[required]" id="id_number" value="{{ Input::old('id_number') }}">
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="mobile">手机号码</label>
                <input type="text" name="mobile" class="form-control validate[required,custom[phoneCN]]" id="mobile" value="{{ Input::old('mobile') }}">
            </div>
        </div>

        <h3>职位信息</h3>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="position">工作岗位</label>
                <input type="text" name="position" placeholder=""
                    class="form-control validate[required]" id="position" value="{{ Input::old('position') }}">
            </div>
            <div class="col-sm-3">
                <label for="company">目前所在单位</label>
                <input type="text" name="company" placeholder=""
                    class="form-control validate[required]" id="company" value="{{ Input::old('company') }}">
            </div>
        </div> 

        <h3>居住地址</h3>
        <div class="form-group row">
            <div class="col-sm-5">
                <label for="street">街道名</label>
                <input type="text" name="street" class="form-control validate[required]" id="street" value="{{ Input::old('street') }}">
            </div>
        </div>                                      
        <div class="form-group row">
            <div class="col-sm-2">
                <label for="city">城市</label>
                <input type="text" name="city" class="form-control validate[required]" id="city" value="{{ Input::old('city') }}">
            </div>
            <div class="col-sm-2">
                <label for="state">省</label>
                <input type="text" name="state" class="form-control validate[required]" id="state" value="{{ Input::old('state') }}">
            </div>
            <div class="col-sm-2">
                <label for="postcode">邮编</label>
                <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="postcode" value="{{ Input::old('postcode') }}">
            </div>                    
        </div>  

        <hr>                
        <h3>激励偏好</h3>
        <span class="help-block">您希望以哪种方式作为您的返点</span>
        <div class="form-group row">
            <div class="col-sm-3">
               {{ Form::select('preference', 
                        array('招待费' => '招待费', '通讯费'=> '通讯费',
                                '差旅费' => '差旅费', '汽油费' => '汽油费', '现金' => '现金',
                                '医药费' => '医药费', '保健品' => '保健品', '其它' => '其它' ),
                        array('class' => 'form-control')) 
                }}
            </div>

        </div>
        <div class="form-group row"> 
            <div class="col-sm-5">
                <label for="other_request">其它要求</label>
                <input type="text" name="other_request" class="form-control" id="other_request">
            </div>
        </div>

        <hr>
        <div class="statement">
            <p>
                关于天添财富系统的特点、收益和风险，您需要仔细阅读               
                <a href="/legal">用户条款</a> 
                以决定天添财富是否可以满足您的要求</p>
            <p class="mt20 mb20">点击 “同意并创建账号” 按钮，表明我:</p>
            <ul>
                <li>同意并接受 <a href="/legal">用户条款</a>。</li>
                <li>确定已经阅读并同意天添财富的 <a href="/coming-soon">用户条款</a>, 及对应的政策, <a href="/legal">用户条款</a> 以及 <a href="/privacy">隐私政策</a></li>
                <li>同意并接受天添财富可以追踪您的网页浏览内容，包括 cookies，并且天添财富可以存储您的交易信息</li>
            </ul>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt40 mb40">同意并创建账号</button>     

        {{ Form::close() }}
    </div>
</div>

@stop

@section('inline-js')
<script>
$(function(){
    $("select").selectBoxIt({
        autoWidth: false,
        theme : 'bootstrap'
    });

    $("#agentFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
