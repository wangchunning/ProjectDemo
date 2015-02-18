@section('title')
Managers
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="flat-panel white-box-body">
            <div id="users-panel">
                <h2 class="mt0">
                    Users
                    @if (check_perm('invite_user', FALSE))
                    <span class="btn pull-right btn-default invite-user-btn"><a href="/admin/users/invite">+ Invite User</a></span>
                    @endif
                </h2>        
                <div class="pannel-body table-responsive">             
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Status</th>
                                @if (check_perms('edit_user,remove_role,view_log', 'OR', FALSE))
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($managers as $manager)
                            <tr>
                                <td>{{ pretty_str($manager->first_name . ' '. $manager->middle_name .' '. $manager->last_name) }}</td>
                                <td>{{ $manager->email }}</td>
                                <td>
                                    {{ $manager->role_name() }}
                                </td>
                                <td class="manager-status">{{ str_replace(array('available', 'busy', 'offline'), array('<font class="label label-success">Available</font>', '<font class="label label-warning">Busy</font>', '<font class="label label-default">Offline</font>'), $manager->manager_status) }}</td>
                                @if (check_perms('edit_user,remove_role,view_log', 'OR', FALSE))
                                <td class="action-block">
                                    <div class="btn-group actions">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-caret-down f14p"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if (check_perm('view_log', FALSE))
                                            <li><a href="/admin/logs?uid={{ $manager->uid }}">Logs</a></li>
                                            @endif
                                            @if (check_perm('edit_user', FALSE))
                                            <li><a href="{{ url('admin/users/edit', array($manager->uid)) }}">Edit</a></li>
                                            @endif
                                            @if (check_perm('remove_role', FALSE))
                                            <li><a class="user-remove" href="{{ url('admin/users/remove', array($manager->uid)) }}">Remove</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">{{ $managers->links() }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <hr>
            @if (check_perm('invite_user', FALSE))
            <h2>
                Outstanding Invites
            </h2> 
            <div id="invite-panel">                  
                <div class="pannel-body table-responsive">             
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Expires</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (count($invites) > 0)
                            @foreach ($invites as $invite)
                                <tr>
                                    <td>{{ $invite->email }}</td>
                                    <td>
                                        {{ id_to_string($invite->roles) }}
                                    </td>
                                    <td class="expires">{{ time_to_tz($invite->expires, Auth::admin()->user()->timezone) }}</td>
                                    <td class="action-block">
                                        <div class="btn-group actions">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-caret-down f14p"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="resend-invite" href="{{ url('admin/users/resend', array($invite->id)) }}">Resend Invite</a></li>
                                                <li><a class="user-remove" href="{{ url('admin/users/invite-remove', array($invite->id)) }}">Remove</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                           <tr>
                                <td colspan="4" align="center">There are no outstanding user invites.</td>
                            <tr>
                        @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">{{ $invites->links() }}</td>
                            <tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@stop

@section('inline-js')
 {{ HTML::script('assets/js/lib/admin/user.js') }}
@stop


