@section('title')
Forgot Password - Management System 
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
            {{ Form::open(array('url' => 'admin/password', 'role' => 'form', 'class' => 'login-form')) }}
                <h3 class="lightblue">Forgot your Password?</h3>

                @if ($errors->has())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-danger">
                    Your email address is incorrect.
                </div>
                @endif
                <div>        
                    <p class="color-666">To receive a Reset Password by email, simply enter email address below and click submit. You MUST use the email address you registered with us</p>
                </div>
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" value="{{ Input::old('email') }}" name="email" required  id="email" placeholder="Enter email">
                </div>
                <button type="submit" class="btn btn-primary mt20 login-lg">Submit</button> <p class="dblock color-999">Or <a class="underline color-999" href="javascript:history.go(-1);">Cancel</a></p>
            {{ Form::close() }}
            <!-- /login form -->
        </div>
    </div>
    <!-- /login -->
</div>
@stop
