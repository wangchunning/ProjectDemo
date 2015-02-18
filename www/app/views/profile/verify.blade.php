@section('title')
个人信息验证
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
<div class="container flat-panel">
    <div class="all-radius white-box-body">
    @include('profile.verify_step')
    <hr class="mt0">
        
        @if ($errors->has() || Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif  

        @if (Session::has('unverified'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p>请验证您的身份信息。感谢注册天添财富</p>
        </div>
        @endif     

        {{ Form::open(array('url' => 'profile/verify', 'role' => 'form', 'id' => 'frm')) }}

            <h4>填写身份证件信息</h4>

	        <div class="form-group row mt20">
	            <div class="col-sm-3">
	                <label for="real_name">真实姓名</label>

						<input type="text" class="form-control" name="real_name" placeholder=""
	                        		value="{{ Input::old('real_name') }}" 
	                        		data-bv-notempty="true"
	                				data-bv-notempty-message="* 请输入真实姓名">


	            </div>
	        </div>
	        <div class="form-group row mt20">
	            <div class="col-sm-3">
	                <label for="real_name">身份证号</label>

						<input type="text" class="form-control" name="id_number" placeholder=""
	                        		value="{{ Input::old('id_number') }}" 
	                        		data-bv-notempty="true"
	                				data-bv-notempty-message="* 请输入身份证号"
	                				data-bv-id="true"
	                				data-bv-id-country="CN"
	                				data-bv-id-message="* 请输入有效身份证号码"
	                				data-bv-remote="true"
	                				data-bv-remote-delay=2
	                				data-bv-remote-url="/valid/unique-id-number"
	                				data-bv-remote-type="post"
	                				data-bv-remote-message="* 用户名已被占用">


	            </div>
	        </div>
	        <hr class="mt40 mb20"> 
            <button type="submit" class="button button-rounded button-flat-primary">提交身份信息</button> 
        
        {{ Form::close() }}
    </div>
</div>
@stop

@section('inline-css')

@stop

@section('inline-js')

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
})
</script>
@stop
