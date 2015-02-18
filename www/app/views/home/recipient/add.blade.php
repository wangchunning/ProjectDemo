@section('title')
添加收款人
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
        {{ Form::open(array('url' => 'recipient/add', 'role' => 'form', 'id' => 'recipientFrm') )}}            
            <h2><i class="fa fa-users"></i>  添加收款人</h2>
            <hr>
            <div id="recipient-pannel">           
                <div class="pannel-body"> 
                    <div class="row ml0 mr0">
                        <div class="col-sm-8">  
                            <h6>收款人详细信息</h6>
                            <div class="form-group row">
                                <label for="account_name" class="col-sm-3 control-label">收款人姓名</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control validate[required]" id="account_name"  name="account_name" value="{{ Input::old('account_name') }}">
                                </div>                  
                            </div>                             
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 control-label">地址</label>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control validate[required]" id="address" placeholder="楼牌号，街道名" name="address" value="{{ Input::old('address') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[required]" id="address_2" placeholder="城市" name="address_2" value="{{ Input::old('address_2') }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[required]" id="address_3" placeholder="省" name="address_3" value="{{ Input::old('address_3') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>                   
                            <div class="form-group row">
                                <label for="postcode" class="col-sm-3 control-label">邮编</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control validate[required,custom[number]]" id="postcode"  name="postcode" value="{{ Input::old('postcode') }}">
                                </div>
                            </div>    
                            <div class="form-group row">
                                <label for="phone" class="col-sm-3 control-label">联系电话</label>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            {{ callingCodeSelecter('phone_code', Input::old('phone_code')) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            {{ Form::select('phone_type', array('Mobile' => '手机', 'Land Line' => '座机'), Input::old('phone_type'), array('class' => 'form-control')) }} 
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control validate[required,custom[phone]]" id="phone"  name="phone" value="{{ Input::old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 control-label">Email<span class="color-737">(Optional)</span></label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control validate[custom[email],ajax[ajaxIfWexchange]]" id="email" placeholder="例如 me@anying.com" value="{{ Input::old('email') }}">
                                    <p class="help-block">我们建议您填写收款人 Email 信息，后续我们会将最新的交易状态通过 Email 通知该收款人</p>
                                </div>
                            </div>                            
                            <hr>                                              
                            <h6>银行信息</h6>
                            <div class="form-group row">
                                <label for="country" class="col-sm-3 control-label">* 国家</label>
                                <div class="col-sm-4">
                                    {{ Form::text('country', Input::old('country'), 
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
                                        <li><input name="currency[]" class="validate[required]" <?php $old_currency = Input::old('currency') ? implode('|', Input::old('currency')) : (isset($bank_info) ? $bank_info->currency : ''); echo stristr($old_currency, $currency) ? 'checked ' : ''; ?> value="{{$currency}}" type="checkbox"> <span>{{ $currency }}</span> <img src="/assets/img/flags/{{$currency}}.png" width ="24" height = "16"></li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="swift_code" class="col-sm-3 control-label">SWIFT/BIC 码 <span class="color-737">(Optional)</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="swift_code" placeholder="SWIFT/BIC 码" name="swift_code" value="{{ Input::old('swift_code') }}">
                                </div>
                            </div>                    
                            <div class="form-group row">  
                                <div class="col-sm-9">
                                    <label for="bank_name">银行名称</label>
                                    <input type="text" class="form-control validate[required]" id="bank_name" value="{{ Input::old('bank_name') }}" name="bank_name" placeholder="银行名称，如中国银行">
                                </div>                     
                            </div>
                            <div class="form-group row">  
                                <div class="col-sm-7">
                                    <label for="branch_name">分行</label>
                                    <input type="text" class="form-control validate[required]" id="bank_branch" value="{{ Input::old('branch_name') }}" name="branch_name" placeholder="分行，如北京市分行">
                                </div> 
                            </div>
                            <span class="help-block">银行地址</span>
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label for="bank_unit_number">房间号</label>
                                    <input type="text" name="bank_unit_number" class="form-control" id="bank_unit_number" value="{{ Input::old('unit_number', (isset($bank_info) ? $bank_info->unit_number : '')) }}">                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="bank_street_number">楼牌号</label>
                                    <input type="text" name="bank_street_number" class="form-control validate[required]" id="bank_street_number" value="{{ Input::old('street_number', (isset($bank_info) ? $bank_info->street_number : '')) }}">
                                </div>
                                <div class="col-sm-8">
                                    <label for="bank_street">街道名</label>
                                    <input type="text" name="bank_street" class="form-control validate[required]" id="bank_street" value="{{ Input::old('street', (isset($bank_info) ? $bank_info->street : '')) }}">
                                </div>
                            </div>                                      
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="bank_city">城市</label>
                                    <input type="text" name="bank_city" class="form-control validate[required]" id="bank_city" value="{{ Input::old('city', (isset($bank_info) ? $bank_info->city : '')) }}">
                                </div>
                                <div class="col-sm-4">
                                    <label for="bank_state">省</label>
                                    <input type="text" name="bank_state" class="form-control validate[required]" id="bank_state" value="{{ Input::old('state', (isset($bank_info) ? $bank_info->state : '')) }}">
                                </div>
                                <div class="col-sm-3">
                                    <label for="bank_postcode">邮编</label>
                                    <input type="text" name="bank_postcode" class="form-control validate[required,custom[number]]" id="bank_postcode" value="{{ Input::old('postcode', (isset($bank_info) ? $bank_info->postcode : '')) }}">
                                </div>                    
                            </div>                            
                            <div class="form-group row">
                                <label for="account_number" class="col-sm-3 control-label">银行账号</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="account_bsb" value="{{ Input::old('account_bsb') }}" name="account_bsb" placeholder="BSB">
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control validate[required]" id="account_number" value="{{ Input::old('account_number') }}" name="account_number" placeholder="银行账号">
                                </div>                        
                            </div>                                                                                                                                                                    
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
                            <p class="help-block">短信验证码已经发送到您的手机<span class="mobile"></span></p>
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
                <button type="submit" class="btn btn-primary btn-lg">添加</button> 或, {{ link_to('recipient', '取消') }}                                            
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
{{ HTML::script('assets/js/lib/swiftcode.js')}}
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
