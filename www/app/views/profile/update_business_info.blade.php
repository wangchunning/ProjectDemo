@section('title')
公司信息注册
@stop

@section('body-tag')
<body class="register">
@stop

@section('content')

<div class="container flat-panel">
    <div class="white-box-body all-radius">

        <h2><i class="fa fa-user"></i>输入您的公司信息<small class="pull-right">* 所有信息都是必填项</small></h2>
        <hr class="mt0">
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif       

        {{ Form::open(array('url' => 'profile/update-business-info', 'role' => 'form', 'id' => 'bussinessFrm')) }}

        <h3>公司信息</h3>
        <span class="help-block">请输入公司信息，我们会对您所填写的信息进行审核</span>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="business_name">公司名称</label>
                <input type="text" name="business_name" class="form-control validate[required]" id="business_name" 
                    value="{{ Input::old('business_name', isset($business->business_name) ? $business->business_name : '') }}">
            </div>
        </div>

        <h3>公司地址</h3>
        <div class="form-group row">
            <div class="col-sm-5">
                <label for="street">街道名</label>
                <input type="text" name="street" class="form-control validate[required]" id="street" 
                    value="{{ Input::old('street', isset($business->addr_street) ? $business->addr_street : '') }}">
            </div>
        </div>                                      
        <div class="form-group row">
            <div class="col-sm-2">
                <label for="city">城市</label>
                <input type="text" name="city" class="form-control validate[required]" id="city" 
                    value="{{ Input::old('city', isset($business->addr_city) ? $business->addr_city : '') }}">
            </div>
            <div class="col-sm-2">
                <label for="state">省</label>
                <input type="text" name="state" class="form-control validate[required]" id="state" 
                    value="{{ Input::old('state', isset($business->addr_state) ? $business->addr_state : '') }}">
            </div>
            <div class="col-sm-2">
                <label for="postcode">邮编</label>
                <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="postcode" 
                    value="{{ Input::old('postcode', isset($business->addr_postcode) ? $business->addr_postcode : '') }}">
            </div>                    
        </div>  


        <hr>

        <button type="submit" class="btn btn-primary btn-lg mt40 mb40">同意并提交信息</button>     

        {{ Form::close() }}
    </div>
</div>

@stop

@section('inline-js')
<script>
$(function(){
    $("select").selectBoxIt({
        autoWidth: false,
        theme : 'bootstrap'
    });

    $("#bussinessFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
})
</script>
@stop
