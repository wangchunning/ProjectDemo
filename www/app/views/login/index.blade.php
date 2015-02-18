@section('title')
登录
@stop

@section('inline-css')
{{ HTML::style('assets/icheck/blue.css') }} 
@stop

@section('body-tag')
<body class="login">
@stop

@section('content')
<div class="container">
    <!-- login -->
    <div class="login-body pb20">
        <div class="login-box">
            <div class="row">
          		<div class="col-sm-6">
            		<!-- login form -->
            		{{ Form::open(array('url' => 'login', 'role' => 'form', 'class' => 'login-form white-box-body', 'id' => 'login-form')) }}
              			
              		<h3 class="mt0 mb20">会员登录</h3>

			        @if ($errors->has() OR Session::has('error'))
			        <div class="alert alert-danger">
			            {{ $errors->has() ? $errors->first() : Session::get('error') }}
			        </div>
			        @endif 
                    
              		<div class="form-group">
                		<label for="user_name">用户名</label>
                		<div class="addon-input-group">
	                        <input type="text" class="form-control" name="user_name" id="user_name" placeholder="用户名"
	                        		value="{{ Input::old('user_name', Cookie::has('user_name') ? Cookie::get('user_name') : '') }}" 
	                        		data-bv-notempty="true"
	                				data-bv-notempty-message="* 请输入用户名"
	                        		>
	                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                       	</div>
              		</div>
              		<div class="form-group">
                		<label for="password">密码</label>
                		<div class="addon-input-group">
	                        <input type="password" class="form-control" name="password" id="password" placeholder="密码"
	                        	data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入密码">
	                		<div class="input-group-addon"><i class="fa fa-key"></i></div>
                		</div>
              		</div>
                    <div class="form-group mt10 mb20">
                        <div class="help-block">
                            <input name="remember" type="checkbox" {{ Cookie::has('remember') ? 'checked' : '' }} value="1" >
                            <small>记住用户名</small>
                        </div>
                    </div>                       
              		<div class="form-group">
                		<button type="submit" class="button button-rounded button-flat-primary">登录</button>
                    	<a href="{{ url('register') }}" class="f12p underline ml15">新用户注册</a></small>
              		</div>
                        
                    <hr class="mb20 mt20">                        
              		
              		<div class="form-group">
                      <small class="help-block"><i class="fa fa-wrench"></i>
                      	<span id="forgot-password">需要帮助？ <a class="underline" href="{{ url('password') }}">找回密码</a></span>
                      </small>                            
              		</div>
            		{{ Form::close() }}
            		<!-- /login form -->
          		</div>
          		
          		<div class="col-sm-6">
            		<div class="login-img mt20">
              			<img src="/assets/img/login-img.jpg" class="img-responsive all-radius">
            		</div>
          		</div>
        	</div>
      	</div>
    </div>
    <!-- /login -->
</div>
@stop

@section('inline-js')
{{ HTML::script('assets/js/vendor/icheck.min.js') }}

<script>
$(function(){
    $('#login-form').bootstrapValidator({
    	live: 'disabled'
    });

	$('input').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
	});

})
</script>
@stop
