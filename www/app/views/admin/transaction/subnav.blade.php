<div class="container">
    <ul class="subnav">
    	@if (check_perms('view_add_fund,view_withdraw,view_fx', 'AND', FALSE))
        <li><a{{ $type ? '' : ' class="active"' }} href="/admin/transaction">All</a></li>
        @endif

        @if (check_perm('view_add_fund', FALSE))
        <li><a{{ $type == 'Deposit' ? ' class="active"' : '' }} href="/admin/transaction/index/Deposit">Deposit</a></li>
        @endif

        @if (check_perm('view_fx', FALSE))
        <li><a{{ $type == 'FX Deal' ? ' class="active"' : '' }} href="/admin/transaction/index/FX Deal">FX Deals</a></li>
        @endif

        @if (check_perm('view_withdraw', FALSE))
        <li><a{{ $type == 'Withdraw' ? ' class="active"' : '' }} href="/admin/transaction/index/Withdraw">Withdraw</a></li>
        @endif
    </ul>
</div>