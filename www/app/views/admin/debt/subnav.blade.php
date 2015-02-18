<div class="container">
    <ul class="subnav">

        @if (check_perm('view_debt', FALSE))
        <li><a{{ Request::is('admin/debt/*') ? ' class="active"' : '' }} href="/admin/debt">借款管理</a></li>
        @endif

    </ul>
</div>