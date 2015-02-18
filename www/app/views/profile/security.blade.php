@section('title')
注册信息验证
@stop

@section('body-tag')
<body class="profile">
@stop
@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
            @include('profile.verify_step')
            <div class="row">
                <div class="col-sm-7">
                    
                    <hr class="mt0">
			        
			        @if ($errors->has() OR Session::has('error'))
			        <div class="alert alert-danger">
			            {{ $errors->has() ? $errors->first() : Session::get('error') }}
			        </div>
			        @endif 
					@if (Session::has('unverified'))
	                <div class="alert alert-danger alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    <p>请验证您的手机号码。感谢注册天添财富</p>
	                </div>
	                @endif 
	                
                    <div>
                        <p class="color-666">
                        	手机短信验证是非常有效、方便的授权验证系统。当您的特定行为需要安全验证时，
                			系统会发送给您一个手机验证码，只有验证码匹配才可继续操作
                        </p>
                    </div>
                    
                    {{ Form::open(array('url' => 'profile/security', 'role' => 'form', 'id' => 'frm', 'class'=>'form-horizontal')) }}
                        
                        <div id="handingMsg"></div>
                        
				        <div class="form-group profile-security row">
				            <div class="col-sm-5">
				                <label for="mobile">手机号码</label>
				                <div class="addon-input-group">
									<input type="text" class="form-control" name="mobile" id="mobile" placeholder="手机号码"
				                        		value="{{ Input::old('mobile') }}" 
				                        		data-bv-notempty="true"
				                				data-bv-notempty-message="* 请输入手机号码"
				                				data-bv-phone="true"
				                				data-bv-phone-country="CN"
				                				data-bv-phone-message="* 请输入有效手机号码"
				                        		>
									<div class="input-group-addon"><i class="fa fa-mobile"></i></div>
								</div>
				            </div>
				        </div>                        

                        <div class="mt20">
                            <p>
                            	在继续操作前，您需要同意接收来自天添财富的短信验证码和来电
                            <h5 class="mt20">手机安全验证</h5>
                            <div class="form-group form-inline row mt10">
                                <div class="col-sm-12 ">
                                    <button id="get-pin-btn" class="button button-rounded button-flat-primary ">获取验证码</button>
             
                                    <input type="text" name="pin" placeholder="请输入验证码" class="form-control ml10" id="pin"
                                    	data-bv-notempty="true"
				                		data-bv-notempty-message="* 请输入验证码"
				                		data-bv-integer="true"
				                		data-bv-integer-message="* 请输入有效验证码">
                                </div> 
                            </div>   
                        </div>    
                        <hr>
                        <button type="submit" class="button button-rounded button-flat-primary mt10">下一步</button>                                    
                    {{ Form::close() }}
                </div>
                <div class="col-sm-5">
                    <div class="all-radius tip-block">
                        <h3 class="mb20"><span class="fa fa-lock"></span> 天添财富很在意用户信息的安全</h3>
                        <p>传统的授权方式取决于你所知道的事情，例如密码，安全提示问题等</p>
                        <p>在此之上，我们利用您所拥有的东西进行验证，例如手机</p>
                        <p>我们将安全码发送到您的手机，这样可以降低盗取、伪造交易的风险</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('inline-css')

@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/profile.js')}}
<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });
	security.init();      
})
</script>
@stop