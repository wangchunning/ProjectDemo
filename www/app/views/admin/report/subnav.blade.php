<div class="container">
    <ul class="subnav">

        @if (check_perm('view_activity_log', FALSE))
        <li><a{{ Request::is('/admin/report/activity-log*') ? ' class="active"' : '' }} href="/admin/report/activity-log">操作日志</a></li>
        @endif

    </ul>
</div>