<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
             <h4 class="modal-title">编辑银行账号信息</h4>
        </div>
    {{ Form::open(array('url' => 'recipient/edit-bank/' . $bank->id, 'role' => 'form', 'id' => 'recipientFrm') )}}                    
        <div class="modal-body">
            <div class="form-group row">
                <label for="country" class="col-sm-3">国家</label>
                <div class="col-sm-3">
                    {{ Form::text('country', Input::old('country', $bank->country), 
                    			array('class' => 'form-control transfer_country validate[required] validate[ajax[ajaxCountry]]',
                    					'placeholder' => '请用英文填写国家，如China, Australia')
                    			) 
                    }}                                                                        
                </div>
            </div>
            <div class="form-group row">
                <label for="currency" class="col-sm-3 control-label">币种</label>
                <div class="col-sm-9">
                    <ul class="list-inline groupCheck">
                    @foreach ($currencies as $currency)
                        <li><input name="currency[]" class="validate[required]" <?php $old_currency = Input::old('currency') ? implode('|', Input::old('currency')) : (isset($bank) ? $bank->currency : ''); echo stristr($old_currency, $currency) ? 'checked ' : ''; ?> value="{{$currency}}" type="checkbox"> <span>{{ $currency }}</span> <img src="/assets/img/flags/{{$currency}}.png" width ="24" height = "16"></li>
                    @endforeach
                    </ul>
                </div>
            </div> 
            <div class="form-group row">
                <label for="swift_code" class="col-sm-3 control-label">SWIFT/BIC 码 <span class="color-737">(选填)</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="swift_code"  placeholder="SWIFT/BIC 码" name="swift_code" value="{{ Input::old('swift_code', $bank->swift_code) }}">
                </div>
            </div>                    
            <div class="form-group row">  
                <div class="col-sm-9">
                    <label for="bank_name">银行名称</label>
                    <input type="text" class="form-control validate[required]" id="bank_name" value="{{ Input::old('bank_name', $bank->bank_name) }}" name="bank_name" placeholder="银行名称，如中国银行">
                </div>                     
            </div>
            <div class="form-group row">  
                <div class="col-sm-7">
                    <label for="branch_name">分行</label>
                    <input type="text" class="form-control validate[required]" id="bank_branch" value="{{ Input::old('branch_name', $bank->branch_name) }}" name="branch_name" placeholder="分行，如北京市分行">
                </div> 
            </div>
            <span class="help-block">银行地址</span>
            <div class="form-group row">
                <div class="col-sm-7">
                    <label for="bank_unit_number">房间号</label>
                    <input type="text" name="bank_unit_number" class="form-control" id="bank_unit_number" value="{{ Input::old('unit_number', $bank->unit_number) }}">                    
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="bank_street_number">楼牌号</label>
                    <input type="text" name="bank_street_number" class="form-control validate[required]" id="bank_street_number" value="{{ Input::old('street_number', $bank->street_number) }}">
                </div>
                <div class="col-sm-8">
                    <label for="bank_street">街道名</label>
                    <input type="text" name="bank_street" class="form-control validate[required]" id="bank_street" value="{{ Input::old('street', $bank->street) }}">
                </div>
            </div>                                      
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="bank_city">城市</label>
                    <input type="text" name="bank_city" class="form-control validate[required]" id="bank_city" value="{{ Input::old('city', $bank->city) }}">
                </div>
                <div class="col-sm-4">
                    <label for="bank_state">省</label>
                    <input type="text" name="bank_state" class="form-control validate[required]" id="bank_state" value="{{ Input::old('state', $bank->state) }}">
                </div>
                <div class="col-sm-3">
                    <label for="bank_postcode">邮编</label>
                    <input type="text" name="bank_postcode" class="form-control validate[required,custom[number]]" id="bank_postcode" value="{{ Input::old('postcode', $bank->postcode) }}">
                </div>                    
            </div>                            
            <div class="form-group row">
                <label for="account_number" class="col-sm-3 control-label">银行账号</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="account_bsb" value="{{ Input::old('account_bsb', $bank->account_bsb) }}" name="account_bsb" placeholder="BSB">
                </div>
                <div class="col-sm-5">
                    <input type="text" class="form-control validate[required]" id="account_number" value="{{ Input::old('account_number', $bank->account_number) }}" name="account_number" placeholder="银行账号">
                </div>                        
            </div>                                                                                   
            <div id="sms-pannel">
                <p class="help-block" id="get-sms-text">为了确保您的交易安全，请先获取短信验证码，输入正确后才能继续</p>
                <hr>
                <div id="confirm-sms-text">
                    <p class="help-block">短信验证码已经发送到您的手机 <span class="mobile"></span></p>
                    <p class="help-block">请输入您的短信验证码</p>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt10">
                        <b>步骤 1  </b> - 获取短信验证码
                    </div>
                    <div class="col-sm-5">
                        <a class="btn btn-primary" id="get-pin-btn">获取短信验证码</a>
                    </div>
                </div>
                <div class="row mt20">
                    <div class="col-sm-6 mt10">
                        <b>步骤 2 </b> - 输入短信验证码
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="sms_pin" id="sms_pin" placeholder="验证码" class="form-control validate[required]">
                    </div>
                    <div class="col-sm-5"> 
                        <a class="btn btn-primary" id="confirm-pin-btn">确认验证码</a>
                    </div>
                </div>
                <hr>
            </div>                    
            <div class="text-center action-panel">
                <button type="submit" class="btn btn-primary btn-lg">提交</button> 或, <a href="javascript:;" data-dismiss="modal" aria-hidden="true">取消</a>                                            
            </div>                        
        </div>
    {{ Form::close() }}
    </div>
</div>
{{ HTML::script('assets/js/typeahead.bundle.min.js') }}
{{ HTML::script('assets/js/lib/lock-rate-confirm.js') }}
{{ HTML::script('assets/js/lib/lock-rate-common.js')}}
{{ HTML::script('assets/js/lib/recipient.js')}}
{{ HTML::script('assets/js/lib/swiftcode.js')}}
{{ HTML::script('assets/js/lib/get-pin.js')}}
<script>
$(function(){
    adjustBankField('{{ $bank->country }}');

    // 表单验证提示
    $("#recipientFrm").validationEngine({
        maxErrorsPerField: 1,
        promptPosition : 'inline',
        addFailureCssClassToField: "has-error",
        ajaxFormValidation : true,
        ajaxFormValidationMethod : 'post',
        ajaxFormValidationURL : '/recipient/edit-bank/{{ $bank->id }}',
        onAjaxFormComplete : function(status, form, resp, options) {
            if (resp.status == 'ok') {
                 location.reload();
            }

            if (resp.status == 'error') {
                $('.action-panel').prepend(
                    '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + resp.data.msg + '</div>'
                );
                return false;
            } 
        }
    });    
})
</script>
 