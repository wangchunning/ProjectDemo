@section('title')
重置密码
@stop

@section('body-tag')
<body class="reset-password">
@stop

@section('content')
<div class="container">
    <div class="password-body">
        <div class="box">
            <div class="row">
                <div class="col-sm-6">
                    {{ Form::open(array('url' => url('password/reset', array($uid, $token)), 'role' => 'form', 'class' => 'password-form white-box-body')) }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="uid" value="{{ $uid }}">
                        <h2<i class="fa fa-key"></i> 重置密码</h2>
                        <p class="text-muted">
                            请输入新密码
                        </p>
                        @if ($errors->has())
                        <p class="text-danger">
                            {{ $errors->first() }}
                        </p>
                        @elseif (Session::has('error'))
                        <p class="text-danger">
                            {{ trans(Session::get('reason')) }}
                        </p>
                        @endif
                        <div class="form-group {{ $errors->first('password', 'has-error') }}">
                            <label for="password">新密码</label>
                            <input id="password" placeholder="新密码" type="password" name="password" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group {{ $errors->first('password_confirmation', 'has-error') }}">
                            <label for="password">再次输入新密码</label>
                            <input id="password_confirmation" placeholder="再次输入新密码" type="password" name="password_confirmation" class="form-control" maxlength="100" required>
                        </div>
                        <button type="submit" class="btn btn-primary form-lg">重置密码</button>
                        
                    {{ Form::close() }}
                </div>
                <div class="col-sm-6">
                    <div class="all-radius" style="background:grey;height:350px;">
                        <img src="#" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
