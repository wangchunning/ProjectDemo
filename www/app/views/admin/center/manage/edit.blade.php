@section('title')
Manager Edit
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="white-box-body all-radius">
            <h2 class="mt0">
                Edit User {{ $manager->first_name }} {{ $manager->middle_name }} {{ $manager->last_name }}
                <span class="back-btn f14p ml15">{{ link_to('admin/users', 'Back') }}</span>
            </h2>
            {{ Form::open(array('url' => url('admin/users/edit', array($manager->uid)), 'role' => 'form', 'id' => 'managerFrm')) }}

                <h3 class="f20p">{{ $manager->first_name }} {{ $manager->middle_name }} {{ $manager->last_name }} Detail</h3>
                <hr class="mt0">
                @if ($errors->has())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif
                <label>Full Name</label>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <input type="text" name="first_name" class="form-control validate[required]" id="first_name" value="{{ Input::old('first_name') ? Input::old('first_name') : $manager->first_name }}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ Input::old('middle_name') ? Input::old('middle_name') : $manager->middle}}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="last_name" class="form-control validate[required]" id="last_name" value="{{ Input::old('last_name') ? Input::old('last_name') : $manager->last_name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control validate[required,custom[email]]" name="email" value="{{ Input::old('email') ? Input::old('email') : $manager->email }}" id="email" placeholder="Enter email">
                    </div>
                </div>
                <h3 class="f20p">Roles</h3>
                <hr class="mt0">
                @foreach ($roles as $role)
                <div class="form-group">
                    <input type="checkbox" id="roles" name="roles[]" value="{{ $role['id'] }}" {{ Input::old('roles') ? (in_array($role['id'], Input::old('roles')) ? 'checked' : '') : (in_array($role['id'], $user_role) ? 'checked' : '') }}> {{ $role['name'] }}
                    <p class="help-block">{{ $role['description'] }}</p>
                </div>
                @endforeach
                <div class="form-group row"> 
                    <div class="col-sm-5">                                              
                        <button type="submit" class="btn btn-primary">Update</button>
                        Or
                        <a class="underline" href="/admin/users">Cancel</a>
                    </div>
                </div> 
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-js')
<script>
$(function(){
    $("#managerFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
