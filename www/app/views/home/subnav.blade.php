<div class="container">
    <ul class="subnav">
        <li><a{{ Request::is('home*') ? ' class="active"' : '' }} href="/home">我的账户</a></li>
        <li><a{{ Request::is('myinvest*') ? ' class="active"' : '' }} href="/myinvest">我的投资</a></li>
        <li><a{{ Request::is('mydebt*') ? ' class="active"' : '' }} href="/mydebt">我的借款</a></li>
        <li><a{{ Request::is('fund*') ? ' class="active"' : '' }} href="/fund/add">充值</a></li>
        <li><a{{ Request::is('withdraw*') ? ' class="active"' : '' }} href="/withdraw/add">提现</a></li>

    </ul>
</div>