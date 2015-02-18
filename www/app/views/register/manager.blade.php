@section('title')
Manager Signup
@stop

@section('body-tag')
<body class="admin-login">
@stop

@section('content')
<div class="container">
    <div class="white-box-body all-radius">
        <h2 class="mt0 mb20">
            Signup for Wexchange Management
        </h2>
        {{ Form::open(array('url' => url('admin/users/signup', array($invite->id, $invite->invite_token)), 'role' => 'form', 'id' => 'managerFrm')) }}
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @elseif (Session::has('error'))
            <div class="alert alert-danger">
                The email is incorrect, please contact <a href="mailto:support@wexchange.com">support@wexchange.com</a>
            </div>
            @endif
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" class="form-control validate[required]" id="first_name" value="{{ Input::old('first_name') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="middle_name">Middle (If any)</label>
                    <input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ Input::old('middle_name') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" class="form-control validate[required]" id="last_name" value="{{ Input::old('last_name') }}">
                </div>
            </div>
            <h2>Password Requirements</h2>
            <hr class="mt0">
            <p class="help-block">To protect your account, please use a password with at least 6 characters and at least 1 number</p>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="email">Email</label>
                    <input readonly type="email" name="email" class="form-control" id="email" value="{{ $invite->email }}">
                </div>
            </div>
            <div class="form-group row {{ $errors->first('password', 'has-error') }}">
                <div class="col-sm-4">
                    <label for="password">Password</label>
                    <input id="password" placeholder="New Password" type="password" name="password" class="form-control validate[required,minSize[8]]" maxlength="100">
                </div>
            </div>
            <div class="form-group row {{ $errors->first('password_confirmation', 'has-error') }}">
                <div class="col-sm-4">
                    <label for="password">Re-enter Password</label>
                    <input id="password_confirmation" placeholder="Confirm Password" type="password" name="password_confirmation" class="form-control validate[required,minSize[8],equals[password]]" maxlength="100">     
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">   
                    <button type="submit" class="btn btn-primary mt20 login-lg">Create Account</button>
                </div>
            </div>
            <p class="help-block">By clicking Create Account, you are indicating that you have read and agree to the <a class="underline" href="/privacy">Terms of Service and Privacy Policy</a></p>
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

