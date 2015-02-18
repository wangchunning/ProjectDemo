@section('title')
Role Management
@stop

@section('content')
    <div class="container">
        <div class="flat-panel white-box-body all-radius">
            <h2 class="mt0">
                Role Management
                <span class="back-btn">{{ link_to('admin/setting', 'Back', array('class' => 'underline')) }}</span>
                @if (check_perm('write_role', FALSE))
                <span class="btn pull-right btn-default add-role"><a class="role-set add" data-toggle="modal" href="#role-modal">+ Add Role</a></span>
                @endif
            </h2>
            @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p>{{ Session::get('error') }}</p>
            </div>
            @endif
            <div id="roles-pannel">                 
                <div class="pannel-body table-responsive">          
                    <table class="table table-hover table-striped role-list">
                        <thead>
                            <tr>
                                <th>Roles</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($roles as $role)
                            <tr class="role-{{ $role->id }}">
                                <td>{{ check_perm('write_role', FALSE) ? link_to('admin/setting/role-permission/' . $role->id, $role->name) : $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td class="action-block">
                                    @if (check_perm('write_role', FALSE))
                                    <div class="btn-group actions">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-caret-down f14p"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="role-set" data-toggle="modal" data-id="{{ $role->id }}" href="#role-modal">Edit</a></li>
                                            <li><a class="role-remove" href="{{ url('admin/setting/role-remove', array($role->id)) }}">Remove</a></li>
                                        </ul>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3">{{ $roles->links() }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if (check_perms('add_role,edit_role', 'OR', FALSE))
    <div class="modal fade" id="role-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="role_id" name="role_id">
                        <label for="email">Name:</label>
                        <input type="email" class="form-control" name="name" id="name" placeholder="Enter Role Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Description:</label>
                        <input type="email" class="form-control" name="description" id="description" placeholder="Enter Role Description">
                    </div>
                    <div class="color-error" id="form-error-tip"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="save-btn" class="btn btn-primary">Save changes</button>
                </div> 
            </div>
        </div>
    </div>
    @endif  
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/admin/role.js') }}
@stop