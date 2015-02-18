@section('title')
系统设置
@stop

@section('content')

    <div class="container mt10">
        <div class="row" id="setting">
            @if (check_perm('view_role', FALSE))
            <div class="col-sm-4 mb20">
                <div class="all-radius white-box-body">
                    <h4 class="mt0 mb20">角色与权限</h4>
                    <p class="desc color-666">设置管理系统的角色及其权限</p>
                    <div class="mb20"><a class="button button-rounded button-flat-primary" href="/admin/setting/roles">角色权限设置</a></div>
                </div>
            </div>
            @endif
            @if (check_perm('view_credit_level', FALSE))
            <div class="col-sm-4 mb20">
                <div class="all-radius white-box-body">
                    <h4 class="mt0 mb20">信用积分</h4>
                    <p class="desc color-666">设置信用积分等级、积分获取等</p>
                    <div class="mb20"><a class="button button-rounded button-flat-primary" href="/admin/setting/credit-level">信用积分设置</a></div>
                </div>
            </div>
            @endif      
        </div>
    </div>
@stop
