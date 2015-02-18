@section('title')
注册金融国有资产
@stop

@section('body-tag')
<body id="home">
@stop

@section('content')
<div class="container">

    <div class="flat-panel">
    {{ Form::open(array('url' => 'debt/add-gov-asset', 'role' => 'form', 'id' => 'addDebtFrm')) }}
        
        <h4 class="mt40">注册金融国有资产</h4>
        <span class="help-block">XXX</span>

        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif             

        <div class="form-group row ">
            <div class="col-sm-6">
                <label for="title">项目名称</label>
                <input type="text" name="title" id="title" placeholder="100 字之内" value="{{ Input::old('title') }}"
                        class="form-control validate[required]">
            </div>
        </div>

        <div class="form-group row ">
            <div class="col-sm-3">
                <label for="expect_price">转让价格</label>
                <input type="text" name="expect_price" id="expect_price" placeholder="" value="{{ Input::old('expect_price') }}"
                        class="form-control validate[required,custom[number]]">
            </div>         
        </div>
        
        <div class="form-group row ">
            <div class="col-sm-3">
                <label for="industry">所属行业</label>
                <input type="text" name="industry" id="industry" placeholder="" value="{{ Input::old('industry') }}"
                        class="form-control validate[required]">
            </div>
            <div class="col-sm-3">
                <label for="area">所属地区</label>
                <input type="text" name="area" id="area" placeholder="" value="{{ Input::old('area') }}"
                        class="form-control validate[required]">
            </div>
        </div>

        <div class="form-group row ">
            <div class="col-sm-3"> 
                <label for="expired_date">截止日期</label>    

                <input type="text" id="expired_date" name="expired_date" placeholder=""
                        value="{{ Input::old('expired_date') }}" class="form-control datepicker validate[required,custom[date]]">

            </div>         

        </div>

        <div class="form-group row ">
            <div class="col-sm-6">
                <textarea id="desc" name="desc" class="form-control" placeholder="详细信息" rows="5">{{ Input::old('desc', '') }}</textarea>
            </div>
        </div> 

        <hr>                

        <div class="statement">
            <p>
                您需要仔细阅读               
                <a href="#">Statement</a> 
                
            </p>
            <p>点击 “同意并提交” 按钮，表明我:</p>
            <ul>
                <li>同意并接受 <a href="#">Statement</a></li>
                <li>确定已经阅读并同意 天添财富 的 <a href="#">用户条款</a>, 及对应的政策, <a href="/legal">Statement</a> 以及 <a href="#">隐私政策</a></li>
            </ul>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt40 mb40">同意并提交</button>   
        
    {{ Form::close() }} 
    </div>
</div>

@stop


@section('inline-css')
{{ HTML::style('assets/jquery-ui/jquery-ui.min.css') }}
@stop


@section('inline-js')
{{ HTML::script('assets/jquery-ui/jquery-ui.min.js') }}   

<script>
$(function(){
    
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth: false
    });

    $( ".datepicker" ).datepicker({
        dateFormat: "yy-mm-dd"
    });

    $("#addDebtFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });

})

</script>
@stop