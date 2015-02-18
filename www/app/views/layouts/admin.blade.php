@include('partials.admin.header')
<body class="admin">
  <!--header-->
  <div class="container">
    <div class="mt20 mb20">
      <div class="btn-group pull-right mt20 f18p">
        <!-- <span class="fa fa-envelope"></span> -->
        <a class="color-737 custom-tooltips" href="{{url('/admin/setting')}}"
        	data-container="body" 
        	data-toggle="tooltip" 
        	data-original-title="系统设置">
        	<span class="fa fa-cog"></span>
        </a>
        <a class="color-737 ml10 mr5 custom-tooltips" href="{{url('/admin/logout')}}" 
        	data-container="body" 
        	data-toggle="tooltip" 
        	data-original-title="注销">
        	<span class="fa fa-power-off"></span>
        </a>
      </div>
      <a href="/admin">{{ HTML::image('assets/img/logo.png', NULL) }}</a>
      </a>
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
                <li><a href="/admin/center">工作台</a></li>
                <li><a href="/admin/debt">借款管理</a></li>
                <li><a href="/admin/finance">资金管理</a></li>
                <li><a href="/admin/audit">认证审核</a></li>
                <li><a href="/admin/customers">用户管理</a></li>
                <li><a href="/admin/content">内容管理</a></li>
                <li><a href="/admin/report">统计报表</a></li>
            </ul>
          </div>
        </nav>
    </div>
    <!-- /nav -->
  </div>
  <!-- /header-->

    @yield('content')

    @include('partials.admin.footer')

    @include('partials.admin.js')
</body>
</html>