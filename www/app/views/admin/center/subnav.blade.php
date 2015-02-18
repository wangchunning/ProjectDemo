<div class="container">
    <ul class="subnav">
        <li><a{{ Request::is('admin/center*') ? ' class="active"' : '' }} href="/admin/center">Overview</a></li>

        @if (check_perms('view_rate,view_basic_rate', 'OR', FALSE))
        <li><a{{ Request::is('admin/rate*') ? ' class="active"' : '' }} href="/admin/rate">Rate</a></li>
        @endif

        @if (check_perm('view_bank', FALSE))
        <li><a{{ Request::is('admin/bank*') ? ' class="active"' : '' }} href="/admin/bank">Banks</a></li>
        @endif

        @if (check_perm('view_user', FALSE))
        <li><a{{ Request::is('admin/users*') ? ' class="active"' : '' }} href="/admin/users">Users</a></li>
        @endif

        @if (check_perm('view_log', FALSE))
        <li><a{{ Request::is('admin/logs*') ? ' class="active"' : '' }} href="/admin/logs">Logs</a></li>
        @endif
    </ul>
</div>