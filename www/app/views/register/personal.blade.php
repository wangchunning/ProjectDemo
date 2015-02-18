@section('title')
新用户注册
@stop

@section('body-tag')
<body class="register">
@stop

@section('content')

<div class="container flat-panel">
    <div class="white-box-body all-radius">

        <h2><i class="fa fa-user"></i>填写注册信息<small class="pull-right">* 所有信息都是必填项</small></h2>
        
        <hr class="mt0">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif            

        {{ Form::open(array('url' => 'register/personal', 'role' => 'form', 'id' => 'frm')) }}

        <div class="form-group row mt20">
            <div class="col-sm-4">
                <label for="user_name">用户名</label>
                <div class="addon-input-group">
					<input type="text" class="form-control" name="user_name" id="user_name" placeholder="用户名"
                        		value="{{ Input::old('user_name') }}" 
                        		data-bv-notempty="true"
                				data-bv-notempty-message="* 请输入用户名"
                				data-bv-stringlength="true"
                				minlength="4"
                				maxlength="16"
                				data-bv-stringlength-message="* 4 ~ 16 个字符"
                				pattern="^[0-9a-zA-Z_]+$"
                				data-bv-regexp-message="* 字母，数字或下划线" 
                				data-bv-remote="true"
                				data-bv-remote-delay=2
                				data-bv-remote-url="/valid/unique-user-name"
                				data-bv-remote-type="post"
                				data-bv-remote-message="* 用户名已被占用">
					<div class="input-group-addon"><i class="fa fa-user"></i></div>
				</div>
            </div>
        </div>
        <div class="form-group row mt20">
            <div class="col-sm-4">                
                <label for="password">登录密码</label>
                <div class="addon-input-group">
					<input type="password" class="form-control" name="password" placeholder="密码" autocomplete="off"
	                        	data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入密码"
	                			data-bv-stringlength="true"
                				minlength="8"
                				data-bv-stringlength-message="* 至少 8 位密码">
					<div class="input-group-addon"><i class="fa fa-key"></i></div>
				</div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4">                
                <label for="repassword" class="sr-only">确认密码</label>                    
                <div class="addon-input-group">
					<input type="password" class="form-control" name="password_confirmation" placeholder="再次输入密码" autocomplete="off"
	                        	data-bv-notempty="true"
	                			data-bv-notempty-message="* 请再次输入密码"
	                			data-bv-identical="true"
				                data-bv-identical-field="password"
				                data-bv-identical-message="* 与登录密码一致" >
					<div class="input-group-addon"><i class="fa fa-key"></i></div>
				</div>                
            </div>
        </div>
        <div class="form-group row mt20">
            <div class="col-sm-4">
                <label for="email">常用邮箱</label>
                <div class="addon-input-group">
					<input type="email" class="form-control" name="email" id="email" placeholder="常用邮箱"
                        		value="{{ Input::old('email') }}" 
                        		data-bv-notempty="true"
                				data-bv-notempty-message="* 请输入常用邮箱"
                				data-bv-emailaddress="true"
                				data-bv-emailaddress-message="* 请输入有效邮箱地址"
                        		>
					<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
				</div>
            </div>
        </div>

        <hr class="mt30 mb20">                

        <div class="statement">
            
            <p class="mt20 mb20">点击 “同意并创建账号” 按钮，表明我:</p>
            <ul>
                <li>同意并接受 <a href="/legal">用户条款</a>。</li>
                <li>确定已经阅读并同意天添财富的 <a href="/coming-soon">用户条款</a>, 及对应的政策, <a href="/legal">用户条款</a> 以及 <a href="/privacy">隐私政策</a></li>
                <li>同意并接受天添财富可以追踪您的网页浏览内容，包括 cookies，并且天添财富可以存储您的交易信息</li>
            </ul>
        </div>
        <button type="submit" class="button button-rounded button-flat-primary mt40 mb40">同意并创建账号</button>     

        {{ Form::close() }}
    </div>
</div>

@stop

@section('inline-js')
{{-- HTML::script('assets/js/vendor/icheck.min.js') --}}

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });
    $( document ).ajaxStart(function() {
    	 $.fancybox.showLoading();
	});
    $( document ).ajaxStop(function() {
		$.fancybox.hideLoading();
	});	
    /*
	$('input').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
	});*/

})
</script>
@stop
