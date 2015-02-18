@section('title')
Email 验证
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">

            <div class="row">
                <div class="col-md-7">

                    {{ Form::open(array('role' => 'form', 'id' => 'frm')) }}

                        <h4>Email 验证</h4>
                        <p class="help-block">
                        	我们会发送给您一封 Email，点击其中的验证链接即可完成验证。
                			如果您没有收到 Email，请查看您的垃圾邮件
                		</p>

                        <hr>
                        <div id="handingMsg"></div>

						<div class="form-group row">
				            <div class="col-sm-8">
				                <label for="email">常用邮箱</label>
				                <div class="addon-input-group">
									<input type="email" class="form-control" name="email" id="email" placeholder="常用邮箱"
				                        		value="{{ $email->value }}" 
				                        		data-bv-notempty="true"
				                				data-bv-notempty-message="* 请输入常用邮箱"
				                				data-bv-emailaddress="true"
				                				data-bv-emailaddress-message="* 请输入有效邮箱地址"
				                        		>
									<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
								</div>
				            </div>
				        </div>

                        <div class="mt10 f12p">
                            <a href="javascript:;" class="send-verify-email-btn">没收到邮件？重新发送验证邮件</a>
                        </div>
                        
                        <hr class="mt30">     
                        <button type='submit' class="button button-rounded button-flat-primary mr10 send-verify-email-btn">获取验证邮件</button>           
                        <a href="{{$email->url}}" target="_blank" type="button" class="button button-rounded button-flat-primary">查看验证邮件</a>                          
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/profile.js')}}
<script>
$(function(){
	profile.init();

	$('#frm').bootstrapValidator({
		//live: 'disabled'
	});
})
</script>    
@stop