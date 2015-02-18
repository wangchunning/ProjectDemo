
		<h4 class="color-f90">您好, {{ $user->user_name }} </h4>
		
		<ul class="list-unstyled list-inline color-737 tooltip-container mb40 f12p">

			<li>账户类型：<span class="color-333">{{ $user->level_desc() }} </span></li>

			<li>验证状态：
			@if ($user->has_all_verified())
			<span class="label label-primary">已完成认证</span>
			@else
			<span class="label label-warning">未完成认证</span>
			@endif
			</li>

		</ul>

