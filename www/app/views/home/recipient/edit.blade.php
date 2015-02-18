@section('title')
编辑收款人
@stop

@section('content')
    @include('home.subnav')
    @include('breadcrumb')
    <div class="container flat-panel">
        <div class="dashboard-body">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif            
        {{ Form::open(array('url' => 'recipient/edit/' . $recipient->id, 'role' => 'form', 'id' => 'recipientFrm') )}}            
            <h2><i class="fa fa-pencil-square-o"></i>编辑收款人信息</h2>
            <hr>
            <div id="recipient-pannel">                 
                <div class="pannel-body"> 
                    <div class="row ml0 mr0">
                        <div class="col-sm-8">                  
                            <h6>收款人信息</h6>
                            <div class="form-group row">
                                <label for="account_name" class="col-sm-3 control-label">姓名</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control validate[required]" id="account_name"  name="account_name" value="{{ Input::old('account_name', $recipient->account_name) }}">
                                </div>                  
                            </div>                             
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 control-label">收款人地址</label>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control validate[required]" id="address" placeholder="街道名，楼牌号" name="address" value="{{ Input::old('address', $recipient->address) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[required]" id="address_2" placeholder="城市" name="address_2" value="{{ Input::old('address_2', $recipient->address_2) }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[required]" id="address_3" placeholder="省" name="address_3" value="{{ Input::old('address_3', $recipient->address_3) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                            <div class="form-group row">
                                <label for="postcode" class="col-sm-3 control-label">邮编</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control validate[required,custom[number]]" id="postcode"  name="postcode" value="{{ Input::old('postcode', $recipient->postcode) }}">
                                </div>
                            </div>    
                            <div class="form-group row">
                                <label for="phone" class="col-sm-3 control-label">联系电话</label>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            {{ callingCodeSelecter('phone_code', Input::old('phone_code', $recipient->phone_code)) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            {{ Form::select('phone_type', array('Mobile' => '手机', 'Land Line' => '座机'), Input::old('phone_type', $recipient->phone_type), array('class' => 'form-control')) }} 
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control validate[required,custom[phone]]" id="phone"  name="phone" value="{{ Input::old('phone', $recipient->phone) }}">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 control-label">Email<span class="color-737">(选填)</span></label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control validate[custom[email],ajax[ajaxIfWexchange]]" id="email" placeholder="如 me@anying.com" value="{{ Input::old('email', $recipient->email) }}">
                                    <p class="help-block">我们建议您填写收款人 Email 信息，后续我们会将最新的交易状态通过 Email 通知该收款人</p>
                                </div>
                            </div>   
                            <hr>                                            
                        </div>
                        <div class="col-sm-4 tips-wrapper">
                            <h4>提示</h4>
                            <p>务必确保填写信息正确，这样才能保证您的汇款及时到达</p>
                            <dl>
                                <dt>完成汇款您需要提供如下信息:</dt>
                                <ul>
                                <li>收款人全称和详细地址</li>
                                <li>收款人银行的详细信息</li>
                                <li>包括收款人银行账户号</li>
                                <li>银行名称和地址</li>
                                <li>银行的 BIC 码，或 bank code/sort code/IFSC.</li>
                                </ul>
                                <dt class="last">同时您还需:</dt>
                                <dd>每个国家都需要 IBAN 和 BIC 码。您的收款人需要向您提供这些信息。我们建议您填写收款人 Email，这样我们会即时向您的收款人更新交易状态 </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="panel-body">
                    <div id="sms-pannel">
                        <h4>安全短信验证</h4>
                        <p class="help-block" id="get-sms-text">
                        为了确保您的交易安全，请先获取短信验证码，输入正确后才能继续
                        </p>
                        <hr>
                        <div id="confirm-sms-text">
                            <p class="help-block">短信验证码已经发送到您的手机 <span class="mobile"></span></p>
                            <p class="help-block">请输入您的短信验证码</p>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mt10">
                                <b>步骤 1 </b> - 获取短信验证码
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-primary" id="get-pin-btn">获取短信验证码</a>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="col-sm-4 mt10">
                                <b>步骤 2 </b> - 输入短信验证码
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="sms_pin" id="sms_pin" placeholder="验证码" class="form-control validate[required]">
                            </div>
                            <div class="col-sm-3"> 
                                <a class="btn btn-primary" id="confirm-pin-btn">确认短信验证码</a>
                            </div>
                        </div>
                        <hr>
                    </div>                    
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">提交</button> 或, {{ link_to('recipient/detail/' . $recipient->id, '取消') }}                                            
            </div>
        {{ Form::close() }}
        </div>
    </div>    
@stop

@section('inline-css')
{{ HTML::style('assets/css/typeahead.js-bootstrap.css') }}
{{ HTML::style('assets/css/selectize.bootstrap3.css') }}
@stop

@section('inline-js')
{{ HTML::script('assets/js/typeahead.bundle.min.js') }}
{{ HTML::script('assets/js/lib/lock-rate-confirm.js') }}
{{ HTML::script('assets/js/lib/lock-rate-common.js')}}
{{ HTML::script('assets/js/selectize.js') }}
{{ HTML::script('assets/js/lib/recipient.js')}}
{{ HTML::script('assets/js/lib/get-pin.js')}}
<script>
$(function() {
    // 表单验证提示
    $("#recipientFrm").validationEngine({
        maxErrorsPerField: 1,
        promptPosition : 'inline',
        addFailureCssClassToField: "has-error"
    });

    $('#phone_code').selectize();
})
</script>
@stop

