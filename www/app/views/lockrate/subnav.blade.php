<div class="container noprint">
    <ul class="subnav">
        <li><a{{ Request::is('fund*') ? ' class="active"' : '' }} href="/fund">存款</a></li>
        <li><a{{ Request::is('lockrate*') ? ' class="active"' : '' }} href="/lockrate">换汇</a></li>
        <li><a{{ Request::is('withdraw*') ? ' class="active"' : '' }} href="/withdraw">取款</a></li>

        <!--li><a{{-- Request::is('fx*') ? ' class="active"' : '' --}} href="/fx">FX</a></li-->
    </ul>
</div>
