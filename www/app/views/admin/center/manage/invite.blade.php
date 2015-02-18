@section('title')
Managers Invite
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="white-box-body all-radius">
            <h2 class="mt0 f32p">
                Invite User
                <span class="back-btn f14p ml15">{{ link_to('admin/users', 'Back') }}</span>
            </h2>
            <h3 class="f20p">User Detail</h3>
            <hr class="mt0">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required  id="email" placeholder="Enter email">
                </div>
            </div>
            <h3 class="f20p">Roles</h3>
            <hr class="mt0">
            @foreach ($roles as $role)
            <div class="form-group">
                <input type="checkbox" id="roles" name="roles[]" value="{{ $role['id'] }}"> {{ $role['name'] }}
                <p class="help-block">{{ $role['description'] }}</p>
            </div>
            @endforeach
            <div class="form-group row"> 
                <div class="col-sm-5">
                    <div class="color-error" id="form-error-tip"></div>                                               
                    <button type="button" id="invite-submit" class="btn btn-primary mt40 mb40">Add</button>
                    Or
                    <a class="underline" href="/admin/users">Cancel</a>
                </div>
            </div> 
        </div>
    </div>
@stop

@section('inline-js')
 {{ HTML::script('assets/js/lib/admin/user.js') }}
@stop


