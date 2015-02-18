@section('title')
企业在线融资申请
@stop

@section('body-tag')
<body id="home">
@stop

@section('content')
@include('breadcrumb')
<div class="container">

    <div class="flat-panel">

    {{ Form::open(array('url' => 'financing/apply', 'role' => 'form', 'id' => 'applyFrm')) }}
    
        <h2><i class="fa fa-user"></i>企业在线融资申请
        <small class="pull-right">* 所有信息都是必填项</small></h2>

        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif             

        <h4 class="mt30">法人基本资料</h4>
        <div class="form-group row ">
            <label class="col-sm-2 mt5"  for="">营业执照</label>
            <div class="col-sm-10" id="yyzz-btn-container">    
                <button id="yyzz-upload-btn" type="button" class="btn btn-default">选择文件</button>
                <span id="yyzz-result-container"></span>  
            </div>          
            
        </div>
        <div class="form-group row ">
            <label class="col-sm-2 mt5"  for="">工商登记</label>
            <div class="col-sm-10" id="gsdj-btn-container">        
                <button id="gsdj-upload-btn"  type="button" class="btn btn-default">选择文件</button>
                <span id="gsdj-result-container"></span> 
            </div>          
            
        </div>
        <div class="form-group row ">
            <label class="col-sm-2 mt5"  for="">税务登记</label>
            <div class="col-sm-10" id="swdj-btn-container">         
                <button id="swdj-upload-btn"  type="button" class="btn btn-default">选择文件</button>
                <span id="swdj-result-container"></span>   
            </div>  
                  
        </div>
        <div class="form-group row ">
             <label class="col-sm-2 mt5" for="">前3年经审计财务报表</label>
            <div class="col-sm-10" id="qsncwbb-btn-container">
                <button id="qsncwbb-upload-btn"  type="button" class="btn btn-default">选择文件</button>
                <span id="qsncwbb-result-container"></span>  
            </div>    
                 
        </div>
        <div class="form-group row ">
            <label class="col-sm-2 mt5"  for="">近期财务报表</label>
            <div class="col-sm-10" id="jqcwbb-btn-container">
                <button id="jqcwbb-upload-btn" type="button" class="btn btn-default">选择文件</button>
                <span id="jqcwbb-result-container"></span>      
            </div>  
               
        </div>

        <div class="form-group row ">
            <div class="col-sm-3">
                <label for="bank_account">贷款发放卡号</label>
                <input type="text" name="bank_account" id="bank_account" placeholder="" value="{{ Input::old('bank_account') }}"
                        class="form-control validate[required]">
            </div>

        </div>
        
        <hr>

        <h4>企业融资需求</h4>
        <div class="form-group row ">
            <div class="col-sm-3">
                <label for="expect_loan_amount">拟贷款金额</label>
                <input type="text" name="expect_loan_amount" id="expect_loan_amount" placeholder="" value="{{ Input::old('expect_loan_amount') }}"
                        class="form-control validate[required]">
            </div>
        </div>
        <div class="form-group row ">
            <div class="col-sm-3"> 
                <label for="loan_during">贷款申请期限</label>    
                <input type="text" id="loan_during" name="loan_during" placeholder=""
                        value="{{ Input::old('loan_during') }}" class="form-control validate[required]">
            </div> 
        </div>
        <div class="form-group row">
            <div class="col-sm-2">
                <label for="pledge">抵质押方式</label>    
               {{ Form::select('pledge', 
                        array('房产' => '房产', '土地'=> '土地',
                                '票据权利' => '票据权利', '生产要素' => '生产要素', '收费权' => '收费权', '专利权' => '专利权',
                                '有确定回收权限的应收账款' => '有确定回收权限的应收账款', '其它' => '其它'),
                        array('class' => 'form-control')) 
                }}
            </div>

        </div>
        <div class="form-group row">
            <div class="col-sm-2">
                <label for="guarantee">担保方式</label>    
               {{ Form::select('guarantee', 
                        array('法人' => '法人', '个人'=> '个人',
                                '担保公司' => '担保公司', '其它' => '其它'),
                        array('class' => 'form-control')) 
                }}
            </div>

        </div>
        <div class="form-group row ">
            <div class="col-sm-6">
                <textarea name="financing_purpose" class="form-control" placeholder="融资用途说明" rows="5">{{ Input::old('financing_purpose', '') }}</textarea>
            </div>
        </div> 



        <hr class="mt40">                

        <button type="submit" class="btn btn-primary btn-lg mb40">确定提交</button>   
        
    {{ Form::close() }} 
    </div>
</div>

@stop


@section('inline-css')
{{ HTML::style('assets/jquery-ui/jquery-ui.min.css') }}
@stop


@section('inline-js')
{{ HTML::script('assets/jquery-ui/jquery-ui.min.js') }}   
{{ HTML::script('assets/plupload/plupload.full.js') }}
<script>
$(function(){
    
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth: false
    });

    $( ".datepicker" ).datepicker({
        dateFormat: "yy-mm-dd"
    });

    $("#applyFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });

    common_file_upload('yyzz-upload-btn', 'yyzz-btn-container', 'license_file', 'yyzz-result-container');
    common_file_upload('gsdj-upload-btn', 'gsdj-btn-container', 'business_register_file', 'gsdj-result-container');
    common_file_upload('swdj-upload-btn', 'swdj-btn-container', 'tax_register_file', 'swdj-result-container');
    common_file_upload('qsncwbb-upload-btn', 'qsncwbb-btn-container', 'years_finance_report_file', 'qsncwbb-result-container');
    common_file_upload('jqcwbb-upload-btn', 'jqcwbb-btn-container', 'recent_finance_report_file', 'jqcwbb-result-container');

})

</script>
@stop