@section('title')
编辑公司信息
@stop

@section('content')
<body class="profile">
    <div class="container flat-panel">
        <div class="all-radius white-box-body" id="business-info"> 
            {{ Form::open(array('url' => '/profile/business-edit/', 'role' => 'form', 'id' => 'businessFrm')) }}
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
                <h2><i class="fa fa-building-o"></i>    公司信息</h2>
                <span class="help-block">请输入您公司的 ABN 或 ACN</span>
                <hr class="mt0">
                <div class="form-group row">
                    <div class="col-sm-5">                
                        <label for="abn">ABN/ACN</label>
                        <input type="text" name="abn" class="form-control validate[required]" id="abn" value="{{ Input::old('abn', isset($user->business->abn) ? $user->business->abn : '') }}">
                    </div>
                </div>   
                <span class="help-block" id="abn-label"></span>                              
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="business_name">公司名称</label>
                        <input readonly type="text" name="business_name" class="form-control validate[required]" id="business_name" value="{{ Input::old('business_name', isset($user->business) ? $user->business->business_name : '') }}">
                    </div>
                </div>
                <h3>公司地址</h3>
                <span class="help-block">我们需要验证您的公司地址</span>
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="unit_number">房间号</label>
                        <input type="text" name="unit_number" class="form-control" id="unit_number" value="{{ Input::old('unit_number', isset($user->business) ? $user->business->unit_number : '') }}">                    
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 {{ $errors->first('street_number', 'has-error') }}">
                        <label for="street_number">楼牌号</label>
                        <input type="text" name="street_number" class="form-control validate[required]" id="street_number" value="{{ Input::old('street_number', isset($user->business) ? $user->business->street_number : '') }}">
                    </div>
                    <div class="col-sm-4 {{ $errors->first('street', 'has-error') }}">
                        <label for="street">街道名</label>
                        <input type="text" name="street" class="form-control validate[required]" id="street" value="{{ Input::old('street', isset($user->business) ? $user->business->street : '') }}">
                    </div>
                </div>                                      
                <div class="form-group row">
                    <div class="col-sm-4 {{ $errors->first('city', 'has-error') }}">
                        <label for="city">城市</label>
                        <input type="text" name="city" class="form-control validate[required]" id="city" value="{{ Input::old('city', isset($user->business) ? $user->business->city : '') }}">
                    </div>
                    <div class="col-sm-2 {{ $errors->first('state', 'has-error') }}">
                        <label for="state">省</label>
                        <input type="text" name="state" class="form-control validate[required]" id="state" value="{{ Input::old('state', isset($user->business) ? $user->business->state : '') }}">
                    </div>
                    <div class="col-sm-2 {{ $errors->first('postcode', 'has-error') }}">
                        <label for="postcode">邮编</label>
                        <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="postcode" value="{{ Input::old('postcode', isset($user->business) ? $user->business->postcode : '') }}">
                    </div>                    
                </div>  
                <hr>                              
                <button type="submit" class="btn btn-primary">提交更新</button> 或 <a href="javascript:;" onclick="javascript:history.back(-1);">取消</a>            
            {{ Form::close() }}                        
        </div>
    </div>
@stop

@section('inline-js')
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('/assets/js/lib/reg-business.js') }}
<script>
$(function(){
    $("#businessFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    }); 

    clearFormErrors();  
})
function clearFormErrors(){
    var selector = $('.form-group.has-error');
    var errors = selector.length;
    $(".form-group .has-error input").on('change', function(){
        $(this).parent().removeClass('has-error');
    });
}
</script>
@stop
