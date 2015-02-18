@section('title')
我的账户 - 天添财富
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')

<div class="container flat-panel">
	<div class="all-radius white-box-body form-horizontal user-detail">

		@include('profile.welcome_header')

		@if (Session::has('message'))
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif

		<hr class="mt0">            
		
		<div class="flat-panel mb40 f12p">
			<h4><i class="fa fa-shield"></i>    账户安全认证</h4>
			<p class="help-block">为了您的交易和个人信息安全，请尽量完成所有安全认证</p>

                <div class="form-group mt40">
                	<div class="col-sm-2 text-center"><i class="fa fa-check-circle lightblue mr5 f14p"></i> 已认证</div>
                	<div class="col-sm-1 pl0 pr0"><i class="fa fa-credit-card"></i> 实名认证</div>
                	<div class="col-sm-4 grey">保障账户安全，确认投资身份</div>
                   	<div class="col-sm-3">
                   		{{ sprintf('%s ** (************* %s)',
                   				mb_substr($profile->real_name, 0, 1), substr($profile->id_number, -4)) }}
                   	</div>
                </div>
                <hr class="mt10 mb20">
                <div class="form-group">
                	<div class="col-sm-2 text-center"><i class="fa fa-check-circle lightblue mr5 f14p"></i> 已认证</div>
                	<div class="col-sm-1 pl0 pr0"><i class="fa fa-mobile f16p mr5"></i> 手机认证</div>
                	<div class="col-sm-4 grey">保障资金安全，获取资金变动等重要通知</div>
                   	<div class="col-sm-3">
                   		{{ sprintf('%s ***** %s',
                   				substr($user->mobile, 0, 3), substr($user->mobile, -4)) }}
                	</div>
                </div>
                <hr class="mt10 mb20">  
                <div class="form-group">
                	<div class="col-sm-2 text-center">
                		@if ($user->is_email_verified)
                		<i class="fa fa-check-circle lightblue mr5 f14p"></i> 已认证
                		@else
                		<i class="fa fa-times-circle orange mr5 f14p"></i> 未认证
                		@endif
                	</div>
                	<div class="col-sm-1 pl0 pr0"><i class="fa fa-envelope-o"></i> 常用邮箱</div>
                	<div class="col-sm-4 grey">获取资金变动通知及投资讯息</div>
                   	<div class="col-sm-3">
                   		{{ $user->email }}
                	</div>
                    <div class="col-sm-2">
                    	@if (!$user->is_email_verified)
                        <a href="profile/email-verify" class="">验证邮箱</a>  <br>
                        <a href="profile/email-verify" class="">修改邮箱</a> 
                        @else
                        <a href="profile/email-verify" class="">修改邮箱</a>    
                    	@endif
                    	
                    </div>
                </div>
                <hr class="mt10 mb20">  
                <div class="form-group">
                	<div class="col-sm-2 text-center">
                		@if (!empty($user->withdraw_password))
                		<i class="fa fa-check-circle lightblue mr5 f14p"></i> 已设置
                		@else
                		<i class="fa fa-times-circle orange mr5 f14p"></i> 未设置
                		@endif
                	</div>
                	<div class="col-sm-1 pl0 pr0"><i class="fa fa-key"></i> 交易密码</div>
                	<div class="col-sm-4 grey">保障资金安全，充值、取款、投资等资金相关操作时使用</div>
                   	<div class="col-sm-3"></div>
                    <div class="col-sm-2">
                    	@if (!empty($user->withdraw_password))
                        <a href="{{url('/profile/change-withdraw-password')}}" class="">修改交易密码</a>  <br>
                        <a href="{{url('/profile/setting-withdraw-password')}}" class="">找回交易密码</a>  
                        @else
                        <a href="{{url('/profile/setting-withdraw-password')}}" class="">设置交易密码</a> 
                    	@endif
                    </div>
                </div>
            </div>
            
            <hr class="mt0">
            
            <div class="flat-panel mb40 f14p">
                <h4 class="mb20"><i class="fa fa-user"></i>    个人信息</h4>
                <div class="form-group">
                    <label class="col-sm-2 text-center">姓名</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ pretty_real_name($profile->real_name) }}</p>
                    </div>
				</div>              
                <div class="form-group">
                    <label class="col-sm-2 text-center">最高学历</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ $profile->education_desc() }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-center">毕业学校</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ empty($profile->education_school) ? '-' : $profile->education_school }}</p>
                    </div>
                </div>

		</div>
		<div class="row ml0 mb40">
			<a class="button button-rounded button-flat-primary col-sm-2" href="/profile/index/edit">编辑个人信息</a>
			<a class="button button-rounded button-flat-primary col-sm-2 ml40" href="/profile/change-pwd">修改登录密码</a>
		</div>
            
		<hr>
            
		<div class="flat-panel mb40 f14p">
			<h4 class="mb20"><i class="fa fa-money"></i> 提现银行卡信息</h4>
                <div class="form-group">
                    <label class="col-sm-2 text-center">账户名</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ pretty_real_name($profile->real_name) }}</p>
                    </div>
				</div>              
                <div class="form-group">
                    <label class="col-sm-2 text-center">账户号</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ empty($bank_account) ? '' : $bank_account->account_number }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-center">银行名称</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ empty($bank_account) ? '' : sprintf('%s %s', $bank_account->bank_name, $bank_account->bank_full_name) }}</p>
                    </div>
                </div>

            </div>
            <div class="row ml0 mb20">
                <a class="button button-rounded button-flat-primary " href="/profile/edit-bank-account">编辑银行卡信息</a>
            </div>
        </div>
    </div>
@stop

@section('inline-css')
 
@stop

@section('inline-js')

<script>
$(function(){

})
</script>
@stop
