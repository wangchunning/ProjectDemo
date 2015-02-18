@section('title')
锁定汇率
@stop

@section('content')
    @include('lockrate.subnav')
    @include('breadcrumb')
    {{ Form::open(array('url' => 'lockrate/money', 'role' => 'form', 'id' => 'lockmoneyFrm') )}}
    <div class="container">
        <div class="dashboard-body">
            <div id="handingMsg">  
            @if ( ! $bank)           
                @if (availableBalance(Exchange::read('lock_currency_have'), FALSE) < Exchange::read('lock_amount'))
                <div class="alert alert-danger">
                    很抱歉，您的 {{ Exchange::read('lock_currency_have') }} 余额不足，我们不支持 {{ Exchange::read('lock_currency_have') }} 存款。您可以选择其它币种换汇
                </div>
                @endif 
            @endif   
            </div>

            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif

            @if (isset($rate_refresh))
            <div id="vip-rate-desc" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p>
                	您是我们的 VIP 会员，我们提供给您的 VIP 汇率: <b class="rate-label"></b></p>
            </div>
            @endif

            <div id="lock-rate-now-pannel" class="flat-panel">        
                <h2>我的交易</h2>
                <table class="table lock-rate-detail">
                    <tbody>
                        <tr>
                            <td width="25%"><span class="desc">锁定汇率:</span></td>                               
                            <td width="50%" class="detail f18p">{{ sprintf('%s/%s <span class="amount-desc lock-rate-label">%s</span>', substr(Session::get('lock_currency'), 0, 3), substr(Session::get('lock_currency'), 3), Session::get('rateForShow')) }} </td>
                            <td width="25%" align="center" rowspan="2">
                                <div id="countdown-pannel">
                                    <div class="only-valid">有效时间</div>
                                    <div id="countdown_dashboard">
                                        <div class="dash minutes_dash">
                                            <div class="digit">0</div>
                                            <div class="digit">0</div>
                                        </div>
                                        <div class="colon">:</div>
                                        <div class="dash seconds_dash">
                                            <div class="digit">0</div>
                                            <div class="digit">0</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="desc">您的交易:</span></td>                               
                            <td class="detail f16p">{{ sprintf('<span class="color-333">%s</span> %s 兑换 <span class="amount-desc" id="amount-want-label">%s</span> %s', number_format(abs(Exchange::read('lock_amount')), 2), currencyName(substr(Session::get('lock_currency'), 0, 3)), number_format(abs(Exchange::read('lock_amount') * Exchange::read('lock_rate')), 2), currencyName(substr(Session::get('lock_currency'), 3))) }} </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="withdraw-notice color-666">
                                    如果您换汇后继续取款，我们将收取您
                                    <span class="color-f60">{{ currencyFormat(Exchange::read('lock_currency_have'), $fee['total'], TRUE) }}</span> 作为手续费
                                </span><br>
                                <span class="withdraw-notice color-666 ml10">
                                    我们为您锁定汇率 5 分钟，请您尽快完成交易
                                </span>             
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="recipient-pannel" class="flat-panel mt20">      
                <h5>取款至</h5>  
                <div> 
                    <div class="form-group">
                        <p class="mt20"><strong>我的余额</strong></p>
                        {{ Form::radio('transfer_to', 'balance', Receipt::session('transfer_to') == 'balance') }}
                        {{ sprintf('%s (可用余额 %s) <img class="currency ml10" src="%s.png" alt="%s" title="%s">', Exchange::read('lock_currency_want'), availableBalance(Exchange::read('lock_currency_want')), URL::asset('assets/img/flags/' . Exchange::read('lock_currency_want')), Exchange::read('lock_currency_want'), Exchange::read('lock_currency_want')) }}
                    </div>
                    <div class="form-group mt20">
                        <p><strong>我的收款人</strong></p>
                        <div class="row account-select">
                            <div class="col-sm-2">
                                <!-- js validate hack -->
                                <input type="hidden" id="transfer_to">
                                <!-- /js validate hack -->
                                {{ Form::radio('transfer_to', 'address', Receipt::session('transfer_to') ? (Receipt::session('transfer_to') == 'address') : TRUE) }}
                                收款人银行账号
                            </div>
                            <div class="col-sm-5">
                                <select class="form-control validate[custom[required_if[transfer_to,address]]]" name="account_id" id="account_id">
                                    <option value="">选择收款人银行账号</option>
                                @foreach ($accounts as $account)
                                    <option data-data='{{ json_encode($account) }}' value="{{ $account->id }}"{{ Receipt::session('recipient', 'account_id') == $account->id ? ' selected' : '' }}>{{ sprintf('%s %s', $account->account_name, $account->account_number) }}</option>
                                @endforeach
                                </select>
                            </div>    
                        </div>                    
                    </div>                        
                    <div class="form-group row">
                        <div class="col-sm-4">
                            {{ Form::radio('transfer_to', 'new', Receipt::session('transfer_to') == 'new') }}
                            新建收款人银行账号
                        </div>
                    </div> 

                    <div id="new-recipient-pannel" class="mt30">
                        <h5 class="mb20">收款人信息</h5>
                        <div class="form-group row recipient-select">
                            <div class="col-sm-5">
                                <!-- js validate hack -->
                                <input type="hidden" id="choose_from" name="choose_from">
                                <!-- /js validate hack -->
                                <select class="form-control validate[custom[required_if_not[choose_from,new]]]" name="recipient_id" id="recipient_id">
                                    <option value="">选择收款人信息</option>
                                @foreach ($recipients as $recipient)
                                    <option value="{{ $recipient->id }}"{{ Receipt::session('recipient', 'recipient_id') == $recipient->id ? ' selected' : '' }}>{{ sprintf('%s (+%s %s)', $recipient->account_name, $recipient->phone_code, $recipient->phone) }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="add_new_recipient" type="button">新建收款人</button>
                            </div>
                        </div>
                        <div id="new-recipient-detail">
                            <div class="form-group row">
                                <label for="account_name" class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control validate[custom[required_if[choose_from,new]]]" id="account_name"  name="account_name" value="{{ Input::old('account_name', Receipt::session('recipient', 'account_name')) }}" placeholder="姓名">
                                </div>                  
                            </div>                          
                            <div class="form-group row">
                                <label for="address" class="col-sm-2 control-label">收款人地址</label>
                                <div class="col-sm-5">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control validate[custom[required_if[choose_from,new]]]" id="address" placeholder="楼牌号，街道名"  name="address" value="{{ Input::old('address', Receipt::session('recipient', 'address')) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[custom[required_if[choose_from,new]]]" id="address_2"  name="address_2" placeholder="城市"  value="{{ Input::old('address_2', Receipt::session('recipient', 'address_2')) }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control validate[custom[required_if[choose_from,new]]]" id="address_3"  name="address_3" placeholder="省"  value="{{ Input::old('address_3', Receipt::session('recipient', 'address_3')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                            <div class="form-group row">
                                <label for="postcode" class="col-sm-2 control-label">邮编</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control validate[custom[required_if[choose_from,new]]]" id="postcode"  name="postcode" value="{{ Input::old('postcode', Receipt::session('recipient', 'postcode')) }}">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="phone" class="col-sm-2 control-label">联系电话</label>
                                <div class="col-sm-5">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            {{ callingCodeSelecter('phone_code', Receipt::session('recipient', 'phone_code') ? Receipt::session('recipient', 'phone_code') : currencyCountry(Exchange::read('lock_currency_want'))) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            {{ Form::select('phone_type', array('Mobile' => '手机', 'Land Line' => '座机'), Input::old('phone_type', Receipt::session('recipient', 'phone_type')), array('class' => 'form-control')) }} 
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control validate[custom[required_if[choose_from,new]],custom[phone]]" id="phone"  name="phone" value="{{ Input::old('phone', Receipt::session('recipient', 'phone')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>                          
                        <hr>                        
                        <h5>银行信息</h5>
                        <div class="form-group row">
                            <label for="country" class="col-sm-2 control-label">* 国家</label>
                            <div class="col-sm-2">
                                {{ Form::text('country', Input::old('country', Receipt::session('recipient', 'country') ? Receipt::session('recipient', 'country') : currencyCountry(Exchange::read('lock_currency_want'))), array('class' => 'form-control transfer_country validate[required] validate[ajax[ajaxCountry]]', 'placeholder' => '国家')) }}                                                                        
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="swift_code" class="col-sm-2 control-label">SWIFT/BIC 码<span class="color-737"><br>(选填)</span></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="swift_code"  name="swift_code" value="{{ Input::old('swift_code', Receipt::session('recipient', 'swift_code')) }}" placeholder="SWIFT/BIC 码">
                            </div>
                        </div>                    
                        <div class="form-group row">  
                            <div class="col-sm-7">
                                <label for="bank_name">银行名称</label>
                                <input type="text" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_name" value="{{ Input::old('bank_nake', Receipt::session('recipient', 'bank_name')) }}" name="bank_name" placeholder="银行名称，如中国银行">
                            </div>                     
                        </div>
                        <div class="form-group row">  
                            <div class="col-sm-5">
                                <label for="branch_name">分行</label>
                                <input type="text" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_branch" value="{{ Input::old('branch_name', Receipt::session('recipient', 'branch_name')) }}" name="branch_name" placeholder="分行，如北京市分行">
                            </div> 
                        </div>
                        <span class="help-block">银行地址</span>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="bank_unit_number">房间号</label>
                                <input type="text" name="bank_unit_number" class="form-control" id="bank_unit_number" value="{{ Input::old('bank_unit_number', Receipt::session('recipient', 'bank_unit_number')) }}">                    
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="bank_street_number">楼牌号</label>
                                <input type="text" name="bank_street_number" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_street_number" value="{{ Input::old('bank_street_number', Receipt::session('recipient', 'bank_street_number')) }}">
                            </div>
                            <div class="col-sm-4">
                                <label for="bank_street">街道名</label>
                                <input type="text" name="bank_street" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_street" value="{{ Input::old('bank_street', Receipt::session('recipient', 'bank_street')) }}">
                            </div>
                        </div>                                      
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="bank_city">城市</label>
                                <input type="text" name="bank_city" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_city" value="{{ Input::old('bank_city', Receipt::session('recipient', 'bank_city')) }}">
                            </div>
                            <div class="col-sm-3">
                                <label for="bank_state">省</label>
                                <input type="text" name="bank_state" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_state" value="{{ Input::old('bank_state', Receipt::session('recipient', 'bank_state')) }}">
                            </div>
                            <div class="col-sm-3">
                                <label for="bank_postcode">邮编</label>
                                <input type="text" name="bank_postcode" class="form-control validate[custom[required_if[transfer_to,new]]]" id="bank_postcode" value="{{ Input::old('bank_postcode', Receipt::session('recipient', 'bank_postcode')) }}">
                            </div>                    
                        </div>     
                        <div class="form-group row">
                            <label for="account_number" class="col-sm-2 control-label">银行账号</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control validate[custom[required_if[transfer_to,new]]]" id="account_bsb" value="{{ Input::old('account_bsb', Receipt::session('recipient', 'account_bsb')) }}" name="account_bsb" placeholder="BSB">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control validate[custom[required_if[transfer_to,new]]]" id="account_number" value="{{ Input::old('account_number', Receipt::session('recipient', 'account_number')) }}" name="account_number" placeholder="银行账号">
                            </div>                        
                        </div>                                     

                        <div class="form-group row">
                            <label for="add-to-book" class="col-sm-2 control-label"></label>
                            <div class="col-sm-5">
                                {{ Form::checkbox('save_to_book', '1', Input::old('save_to_book', Receipt::session('recipient', 'save_to_book')) ? Input::old('save_to_book', Receipt::session('recipient', 'save_to_book')) : TRUE) }} <small>添加到我的收款人</small>
                            </div>                  
                        </div>                          
                                                                                                              
                    </div>
                </div>
                <hr>
            </div>

            <div id="additional-pannel" class="flat-panel">        
                <h5 class="mb30">其它信息</h5>
                <div class="form-group row">
                    <label for="reason" class="col-sm-2 control-label">取款原因</label>
                    <div class="col-sm-3">
                        {{ Form::select('reason', transferReasons(), Input::old('reason', Receipt::session('reason') ? Receipt::session('reason') : $reason), array('class' => 'form-control validate[required]')) }}                        
                    </div>
                </div>                    
                <div class="form-group row">
                    <label for="message" class="col-sm-2 control-label">留言</label>
                    <div class="col-sm-6">
                        <textarea placeholder="20 字之内" id="message" name="message" class="form-control" rows="5">{{ Input::old('message', Receipt::session('message')) }}</textarea>
                    </div>
                </div>   
                <div class="form-group row">
                    <label for="notice" class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        {{ Form::checkbox('send_sms', '1', Input::old('send_sms', Receipt::session('recipient', 'send_sms')) ? Input::old('send_sms', Receipt::session('recipient', 'send_sms')) : TRUE) }} <span class="color-737">交易完成后短信提醒</span>                              
                    </div>
                </div>                                   
            </div>
            <div class="text-center">
                <input type="hidden" name="currency" value="{{ substr(Session::get('lock_currency'), 3) }}">
                <button type="submit" class="btn btn-primary btn-lg pl50 pr50">下一步</button> 或 {{ link_to('/lockrate', '取消') }}                                            
            </div>
        </div>
    </div> 
    {{ Form::close() }}
    <div class="modal fade" id="refresh-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background-color:#fefbed;">
                    <h5>警告!</h5>
                    <p>我们抱歉的通知您，您锁定的汇率超过了 5 分钟，已经过期，您可以选择刷新汇率后继续完成交易</p>
                    <p class="mt20">您要刷新汇率么？</p>
                    <p class="mt20">
                        <a class="btn btn-primary" href="javascript:;" id="refresh-rate-btn">刷新汇率</a>
                        或者
                        {{ link_to('lockrate', '取消') }}
                    </p>
                </div>
            </div>
        </div>
    </div>   
@stop

@section('inline-css')
{{ HTML::style('assets/css/typeahead.js-bootstrap.css') }}
{{ HTML::style('assets/css/selectize.bootstrap3.css') }}
@stop

@section('inline-js')
{{ HTML::script('assets/js/selectize.js') }}
{{ HTML::script('assets/js/typeahead.bundle.min.js') }}
{{ HTML::script('assets/js/lib/lock-rate-common.js')}}
{{ HTML::script('assets/js/jquery.lwtCountdown-1.0.js') }}
{{ HTML::script('assets/js/lib/lock-rate-money.js')}}
{{ HTML::script('assets/js/lib/recipient.js')}}
{{ HTML::script('assets/js/lib/swiftcode.js')}}
<script>
var mins = {{ intval((Exchange::read('lock_time') - Carbon::now()->timestamp) / 60) }}; 
var secs = {{ (Exchange::read('lock_time') - Carbon::now()->timestamp) % 60}};
var rate_refresh = {{ isset($rate_refresh) ? 1 : 0 }};

$(function(){
    if (rate_refresh == 1) {
        $('#refresh-rate-btn').trigger('click');
    };

@if (Input::old('country', Receipt::session('recipient', 'country')))
    adjustBankField('{{ Input::old('country', Receipt::session('recipient', 'country')) }}');    
@endif

    // 初始表单验证
    $("#lockmoneyFrm").validationEngine({
        ajaxFormValidation : true,
        ajaxFormValidationMethod : "post",
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error",
        onBeforeAjaxFormValidation : function(){
            $.fancybox.showLoading();
        },
        onAjaxFormComplete : function(status, form, json){
            $.fancybox.hideLoading();
            if (json.status == 'error') {
                $('#handingMsg').html('<div class="alert alert-danger">' + json.data.msg + '</div>');
                $("html,body").animate({scrollTop: $('.dashboard-body').offset().top}, 500);
                return;
            }
            window.location.href="/lockrate/confirm";             
        }
    });

    $('#phone_code').selectize();

    // 初始地址簿
    $('#account_id').selectize({
        render : {
            option : function(item, escape) {
                return '<div><strong>' + item.account_name + '</strong> <span>' + item.account_number + '</span><p>' + item.bank_name + '-'+ item.branch_name  + '</p></div>';
            }
        },
        onChange : function() {
            // 兼容表单验证
            $('.account-select .selectize-control').removeClass('validate[custom[required_if[transfer_to,address]]]');
            $('.account-select .formErrorContent').remove();
        }
    });

    // 初始联系人
    var $recipient = $('#recipient_id').selectize({
        onChange : function(value) {
            if (value != '') {
                $('#choose_from').val(value);
                $('#new-recipient-detail').hide();

                // 兼容表单验证
                $('.recipient-select .selectize-control').removeClass('validate[custom[required_if_not[choose_from,new]]]');
                $('.recipient-select .formError').remove();
            }else{
                $('#choose_from').val('new');
                $('#new-recipient-detail').show();
            };     
        }
    });

    // 点击展开recipient detail
    $('#add_new_recipient').on('click', function(){
        $('#new-recipient-detail').show();
        $('#choose_from').val('new');
        $recipient[0].selectize.clear();
    });
})

</script>

@stop