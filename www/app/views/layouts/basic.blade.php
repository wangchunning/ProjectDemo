@include('partials.public.header')

@yield('body-tag')

<!--header-->
<div class="container">
	<div class="mt20 mb20">
	
		<div class="btn-group pull-right mt20 f18p">       
			@if (is_login(User::LABEL) AND ! sessionTimeout())
			<a class="color-666 custom-tooltips" href="/profile" 
				data-container="body" 
				data-toggle="tooltip" 
				data-original-title="个人信息">
				<span class="fa fa-home"></span>
			</a>
			<a class="color-666 ml10 mr5 custom-tooltips" href="/logout" 
				data-container="body" 
				data-toggle="tooltip" 
				data-original-title="注销">
				<span class="fa fa-power-off"></span>
			</a>
			@else
			<a class="btn btn-xs btn-link color-666 ml10" href="/login" data-container="body">登录</a>
			<a class="btn btn-xs btn-link color-666 ml10 mr5" href="/register">注册</a>
			@endif
		</div>
		
		<a href="/"><img src="/assets/img/logo.png"  /></a>
	
	</div>
	
    <!--nav-->
	<div class="dashboard-nav">
		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				
				<ul class="nav navbar-nav">
					
					@if (is_login(User::LABEL) AND ! sessionTimeout())
	            	<li><a href="/home">我的账户</a></li>
					@else
					<li><a href="/">首页</a></li>
					@endif
					
					<li class="dropdown" {{ Request::is('/invest') ? ' class="on"' : '' }}>
						<a href="/invest" class="dropdown-toggle" data-toggle="dropdown">我要投资 <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
			                <li><a href="/invest-list-pawn">抵押贷</a></li>
			                <li><a href="#">学生贷</a></li>
			                <li><a href="#">朋友贷</a></li>
						</ul>
					</li>				
					<li class="dropdown" {{ Request::is('/debt') ? ' class="on"' : '' }}>
						<a href="/debt" class="dropdown-toggle" data-toggle="dropdown">我要借款 <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
			                <li><a href="/debt/pawn">抵押贷</a></li>
			                <li><a href="#">学生贷</a></li>
			                <li><a href="#">朋友贷</a></li>
						</ul>
					</li>				
					<li{{ Request::is('/how-it-works') ? ' class="on"' : '' }}><a href="/how-it-works">新手指引</a></li>					
					<li class="dropdown" {{ Request::is('/about') ? ' class="on"' : '' }}>
						<a href="/about" class="dropdown-toggle" data-toggle="dropdown">关于我们 <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
			                <li><a href="#">管理团队</a></li>
			                <li><a href="#">最新动态</a></li>
			                <li><a href="#">招贤纳士</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</div><!-- /nav -->
	
</div><!-- /header-->

@yield('content')

@include('partials.public.footer')

@include('partials.public.js')

