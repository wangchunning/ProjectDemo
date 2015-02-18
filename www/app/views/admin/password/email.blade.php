@section('title')
Forgot Password - Management System 
@stop

@section('body-tag')
<body class="admin-login">
@stop

@section('content')
<div class="container">
    <div class="login-body">
        <div class="white-box-body all-radius login-form">
            <h3 class="lightblue">Forgot your Password?</h3>
            <div class="by-email">
                <p class="f16p pt20">Create a new password</p>
                <p class="color-666 f13p">We just sent you an email at <a class="underline">{{$email->value}}</a>. Click the link in the email to create a new password.</p>
                <p class="tcenter pt20"><a href="{{$email->url}}" target="_blank" type="button" class="btn btn-primary login-lg">Check E-mail</a></p>
                <p class="f16p pt20">Didn't receive our email?</p>
                <ul class="color-666 f13p no-email">
                    <li>Check your email account's junk or spam folder.</li>
                    <li>We can <a class="underline" href="{{url('admin/password')}}">resend the email.</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop
