@section('title')
登录 - 天添财富后台管理系统
@stop

@section('body-tag')
<body class="admin-login">
@stop

@section('content')
<div class="container">
    <!-- login -->
    <div class="login-body">

        <div class="white-box-body all-radius">
            <!-- login form -->
            {{ Form::open(array('url' => 'admin/login', 'role' => 'form', 
            					'class' => 'login-form', 'id' => 'login-form'
            					)) }}

            <h3 class="blue mt0 mb30 ">{{ HTML::image('assets/img/logo.png', NULL) }}</h3>

	        @if ($errors->has() OR Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif  

            <div class="form-group row">
				<div class="col-sm-10">
					<label for="user_name">用户名</label>
					<input type="text" class="form-control" value="{{ Input::old('user_name') }}" name="user_name" id="user_name" placeholder="用户名"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入用户名">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-10">
					<label for="password">密码</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="密码"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入密码">
				</div>
			</div>
			<div class="form-group row mt50 mb20">
				<div class="col-sm-10">
					<button type="submit" class="button button-rounded button-flat-primary"> 登录  </button>
				</div>
			</div>
			
            {{ Form::close() }}
            <!-- /login form -->
        </div>
    </div>
    <!-- /login -->
</div>
@stop

@section('inline-js')
<script>
$(function(){

    $('#login-form').bootstrapValidator({
    	live: 'disabled'
    });
})
</script>
@stop
