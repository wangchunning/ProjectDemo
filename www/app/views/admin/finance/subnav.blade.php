<div class="container">
    <ul class="subnav">

        @if (check_perm('view_withdraw', FALSE))
        <li><a{{ Request::is('admin/finance/withdraw*') ? ' class="active"' : '' }} href="/admin/finance/withdraw">提现管理</a></li>
        @endif

    </ul>
</div>