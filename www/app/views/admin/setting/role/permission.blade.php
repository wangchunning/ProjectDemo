@section('title')
Edit Role
@stop

@section('content')
    <div class="container mt10">
        <div class="flat-panel white-box-body all-radius role-manage">
            <h2 class="mt0">
                Edit Role
                <span class="back-btn">{{ link_to('admin/setting/roles', 'Back', array('class' => 'underline')) }}</span>
            </h2>
            <h3 class="color-666 f20p">Roles: <span class="ml15 f16p">{{ $role->name }}</span></h3>
            <h3 class="color-666 f20p">Description: <span class="ml15 f16p">{{ $role->description }}</span></h3> 
            <hr>

            @if (Session::has('edit'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>Success!</h4>
                <p>Your changes have been successfully updated.</p>
            </div>
            @endif

            {{ Form::open(array('url' => url('admin/setting/role-permission/' . $role->id), 'role' => 'form', 'id' => 'roleFrm')) }}
            @foreach(Config::get('authorize') as $m => $section)
            <div class="row module">
                <h4 class="col-sm-12"><span class="mr5 f14p"><input type="checkbox" class="sel_all"></span>{{ $section['title'] }}</h4>
                @foreach($section['actions'] as $a => $action)
                    @if (is_array($action))
                    <div class="perm-required">
                    @foreach($action as $sec => $act)                    
                        <div class="col-sm-4 col-md-3">
                            <input type="checkbox" name="action[]" {{ in_array($sec, Input::old('action', $perms)) ? 'checked' : '' }} value="{{ $sec }}"> {{ $act }}
                        </div>
                    @endforeach
                    </div>
                    @else
                    <div class="col-sm-4 col-md-3">
                        <input type="checkbox" name="action[]" {{ in_array($a, Input::old('action', $perms)) ? 'checked' : '' }} value="{{ $a }}"> {{ $action }}
                    </div>
                    @endif
                @endforeach
            </div>
            <hr>
            @endforeach
            <div class="row">
                <div class="col-sm-4">
                    <button class="btn btn-primary mr10" type="submit">Save</button>
                    Or
                    <a class="underline" href="/admin/setting/roles">Cancel</a>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/admin/role.js') }}
@stop
