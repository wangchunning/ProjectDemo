@include('partials.public.header')

@yield('body-tag')
    <!--header-->
<div class="container">
    <div class="mt20 mb20 header">
        <div class="btn-group pull-right mt20">
            @if (is_login() AND ! sessionTimeout())
                <a href="/profile" data-toggle="tooltip" data-original-title="个人信息"><span class="fa fa-user"></span></a>
                <a href="/logout" data-toggle="tooltip" data-original-title="注销"><span class="fa fa-power-off"></span></a>
            @elseif (is_login('manager') AND ! sessionTimeout())
                <a href="/admin/setting" data-toggle="tooltip" data-original-title="设置"><span class="fa fa-cog"></span></a>
                <a href="/admin/profile" data-toggle="tooltip" data-original-title="个人信息"><span class="fa fa-user"></span></a>
                <a href="/admin/logout" data-toggle="tooltip" data-original-title="注销"><span class="fa fa-power-off"></span></a>
            @else
                <ul class="list-unstyled list-inline">
                    <li><a class="f12p" href="http://www.anying.com/index.php/about_anying/aboutus">联系我们</a></li>
                    <li><a class="f12p ml5 mr5" href="/login">登录</a></li>
                    <li><a class="btn btn-xs btn-link signup" href="/register">免费注册</a></li>
                </ul>
            @endif
            </div>
        <a href="/"><img src="assets/img/logo.png" width="276" height="72" /></a>
        <!--img src="assets/img/national-grey.png" width="150" height="62" /-->
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
                <ul class="nav navbar-nav navbar-right">
                    <li class="on"><a href="/">首页</a></li>
                    <li><a href="http://www.anying.com/index.php/about_remit">国际汇款</a></li>
                    <li><a href="http://www.anying.com/index.php/about_exchange">外币兑换</a></li>
                    <li><a href="http://www.anying.com/index.php/about_anying">关于安盈</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- /nav -->
</div>
  <!-- /header-->

    @yield('content')

    @include('partials.public.footer')

    @include('partials.public.js')
</body>
</html>