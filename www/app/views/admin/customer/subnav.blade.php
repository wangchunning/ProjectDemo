<div class="container">
    <ul class="subnav">
        <li><a href="/admin/customers"{{ Request::is('*customers') ? ' class="active"' : '' }}>All</a></li>
        <li><a href="/admin/customers/pending"{{ Request::is('*customers/pending') ? ' class="active"' : '' }}>Pending({{ customerQuantity('pending') }})</a></li>
        <li><a href="/admin/customers/active"{{ Request::is('*customers/active') ? ' class="active"' : '' }}>Active({{ customerQuantity('active') }})</a></li>
        <li><a href="/admin/customers/inactive"{{ Request::is('*customers/inactive') ? ' class="active"' : '' }}>Inactive({{ customerQuantity('inactive') }})</a></li>
        <li><a href="/admin/customers/archived"{{ Request::is('*customers/archived') ? ' class="active"' : '' }}>Archived({{ customerQuantity('archived') }})</a></li>
    </ul>
</div>