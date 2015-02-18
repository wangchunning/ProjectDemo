@section('title')
Edit Profile - Manager
@stop

@section('content')
    @include('admin.manager_info')
    <div class="container">
        <div class="all-radius white-box-body">
            {{ Form::open(array('url' => url('admin/profile/edit'), 'role' => 'form', 'id' => 'profileFrm')) }}
            <h3>{{ $manager->first_name }} Profile</h3>
            <hr class="mt0">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" class="form-control validate[required]" id="first_name" value="{{ Input::old('first_name') ? Input::old('first_name') : $manager->first_name }}">
                </div>
                <div class="col-sm-2">
                    <label for="middle_name">Middle (If any)</label>
                    <input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ Input::old('middle_name') ? Input::old('middle_name') : $manager->middle}}">
                </div>
                <div class="col-sm-3">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" class="form-control validate[required]" id="last_name" value="{{ Input::old('last_name') ? Input::old('last_name') : $manager->last_name }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control validate[required,custom[email]]" name="email" value="{{ Input::old('email') ? Input::old('email') : $manager->email }}" id="email" placeholder="Enter email">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="password">Password</label>
                    <input id="password" placeholder="New Password" type="password" name="password" class="form-control validate[minSize[8],equals[password_confirmation]]" maxlength="100">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="password">Re-enter Password</label>
                    <input id="password_confirmation" placeholder="Confirm Password" type="password" name="password_confirmation" class="form-control validate[minSize[8],equals[password]]" maxlength="100">     
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="time-zone">Time zone</label>
                    {{ timezone_menu(Input::old('timezone') ? Input::old('timezone') : $manager->timezone) }}
                </div>
            </div>
            <hr class="mt0">
            <div class="row ml0">
                <button class="btn btn-primary col-sm-2 mr10" type="submit">Save</button>
                Or
                <a class="underline" href="/admin/profile">Cancel</a>
            </div>
        </div>
    </div>
@stop

@section('inline-js')
 {{ HTML::script('assets/js/lib/admin/user.js') }}
 <script>
$(function(){
    $("#profileFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
