@section('title')
Reset Password - Management System 
@stop

@section('body-tag')
<body class="admin-login">
@stop

@section('content')
<div class="container">
    <!-- login -->
    <div class="login-body">
        <div class="white-box-body all-radius">
            <!-- login form -->
            {{ Form::open(array('url' => url('admin/password/reset', array($uid, $token)), 'role' => 'form', 'class' => 'login-form')) }}
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="uid" value="{{ $uid }}">

                <h3 class="lightblue">Password Reset</h3>

                @if ($errors->has())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @elseif (Session::has('error'))
                <div class="alert alert-danger">
                    {{ trans(Session::get('reason')) }}
                </div>
                @endif
                <div>        
                    <p class="color-666">To reset your password, simply enter your new password.</p>
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <label for="password">Password</label>
                    <input id="password" placeholder="New Password" type="password" name="password" class="form-control" maxlength="100" required>
                </div>
                <div class="form-group {{ $errors->first('password_confirmation', 'has-error') }}">
                    <label for="password">Re-enter Password</label>
                    <input id="password_confirmation" placeholder="Confirm Password" type="password" name="password_confirmation" class="form-control" maxlength="100" required>
                </div>
                <button type="submit" class="btn btn-primary mt20 login-lg">Reset</button>
            {{ Form::close() }}
            <!-- /login form -->
        </div>
    </div>
    <!-- /login -->
</div>
@stop

