@section('title')
注册 
@stop

@section('body-tag')
<body class="register">
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="white-box-body all-radius text-center register-type-intro">
                <h2>普通账户</h2>
                <span class="help-block">针对于个人、企业用户的投资、债务转让、理财等需求</span>
                <a href="/register/personal" class="btn btn-primary">开始注册</a>
                <div class="text-left">
                    <span class="help-block">免费注册</span>
                    <ul>
                        <li>为您提供最新的理财信息</li>
                        <li>帮助您的企业找到最合适的投资项目</li>
                        <li>借助我们的平台，让您的债务转让更加顺畅</li>
                        <li>帮助您的企业建立可靠的融资渠道</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="white-box-body all-radius text-center register-type-intro">
                <h2>客户经理账户</h2>
                <span class="help-block">如果您想成为我们中的一员，欢迎加入</span>
                <a href="/register/agent" class="btn btn-primary">开始注册</a>                
                <span class="help-block text-left">
                    我们会请您填写一些额外的信息，以便我们能够更加确切的满足您的需求。
                    <br>
                    您所提交的信息我们会进行审核，审核通过后才能正常使用系统    
                </span>
            </div>
        </div>
    </div>
</div>    
@stop