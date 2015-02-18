<div class="container noprint">
    <ul class="subnav">
        <li><a{{ Request::is('profile*') ? ' class="active"' : '' }} href="/fund">我的账户</a></li>
        <li><a{{ Request::is('lockrate*') ? ' class="active"' : '' }} href="/lockrate">资金管理</a></li>
        <li><a{{ Request::is('withdraw*') ? ' class="active"' : '' }} href="/withdraw">投资管理</a></li>
        <li><a{{ Request::is('lockrate*') ? ' class="active"' : '' }} href="/lockrate">借款管理</a></li>
        <li><a{{ Request::is('withdraw*') ? ' class="active"' : '' }} href="/withdraw">账户管理</a></li>
    </ul>
</div>
