@section('title')
升级账户
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
            <h2><i class="fa fa-upload"></i>    账号升级</h2>
            <hr class="mt0">
            <p><strong>公司账号</strong> 包括个人账号 (Personal) 的所有功能。同时您还可以享受：</p>
            <ul>
                <li>更低的手续费</li>                
                <li>更加及时的客户服务及突发事件响应</li>
                <li>更多更实用的功能</li>
                <li>以公司的名义进行交易</li>
                <li>提供多成员管理模式</li>
            </ul>
            <span class="help-block mt40">已经拥有 Anying 账号了么?</span>
            <div class="mt40">
                <a href="/profile/upgrade-business" class="btn btn-primary btn-lg">现在升级</a> 或, {{ link_to('/home', '取消') }}            
            </div>
        </div>
    </div>
@stop