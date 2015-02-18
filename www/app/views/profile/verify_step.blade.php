<div class="verify-step mb20 deepblue">
	<h2 class="f18p mt0">为了保证您的资金安全，我们需要验证您的个人信息</h2>
	<div class="step">
		<div class="{{ !empty($user->mobile) ? 'color-666' : 'deepblue' }}">
			<div class="circle">
				{{ !empty($user->mobile) ? '<span class="fa fa-check"></span>' : '<span>1</span>' }}
			</div>
			<span>手机验证</span>
		</div>
		<div class="line"><span class="fa fa-long-arrow-right {{ $user->is_id_verified ? 'color-666' : 'deepblue' }}"></span></div>
		<div class="deepblue">
			<div class="circle">
				<span>2</span>
			</div>
			<span>身份验证</span>
		</div>
	</div>
</div>