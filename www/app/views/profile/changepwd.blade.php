@section('title')
修改登录密码
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
@include('breadcrumb')

    <div class="container flat-panel">
        <div class="all-radius white-box-body">
        
	        @if ($errors->has() OR Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif 
	        
            {{ Form::open(array('url' => url('/profile/change-pwd'), 'role' => 'form', 'id' => 'frm')) }}
                <h4><i class="fa fa-key"></i>    修改登录密码</h4>
                <hr class="mt0">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="email">当前密码</label>
                        <input type="password" name="old_password" class="form-control" placeholder="旧密码"
                        		data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入旧密码"
	                			data-bv-stringlength="true"
                				minlength="8"
                				data-bv-stringlength-message="* 至少 8 位密码">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">                
                        <label for="password">新密码</label>
                        <input type="password" name="password" class="form-control" placeholder="新密码"
                        		data-bv-notempty="true"
	                			data-bv-notempty-message="* 请输入新密码"
	                			data-bv-stringlength="true"
                				minlength="8"
                				data-bv-stringlength-message="* 至少 8 位密码">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">                
                        <label for="repassword" class="sr-only">再次输入新密码</label>                    
                        <input type="password" name="password_confirmation" class="form-control" placeholder="再次输入新密码"
                        		data-bv-notempty="true"
	                			data-bv-notempty-message="* 请再次输入密码"
	                			data-bv-identical="true"
				                data-bv-identical-field="password"
				                data-bv-identical-message="* 与登录密码一致">
                    </div>
                </div>
                <hr class="mt20 mb30">                
                <button type="submit" class="button button-rounded button-flat-primary">更新登录密码</button>                       
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-js')
<script>
$(function(){
	
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });

});

</script>    
@stop