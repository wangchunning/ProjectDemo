@section('title')
锁定汇率确认信息
@stop

@section('content')
    @include('lockrate.subnav')
    @include('breadcrumb')
    {{ Form::open(array('url' => 'lockrate/confirm')) }}
    <div class="container">
        <div class="dashboard-body">
            @if ($errors->has() OR Session::has('error'))
            <div class="alert alert-danger">
                {{ $errors->has() ? $errors->first() : Session::get('error') }}
            </div>
            @endif
            <div class="flat-panel receipt-item-panel"> 
                <h2 class="mb20">确认交易信息</h2>
                @if ($bank)
                    @if ($payment['balance'] > 0)            
                    <div id="payment-method-panel">
                        <p>
                        您的 {{ Exchange::read('lock_currency_have') }} 余额尚有 
                        <strong>{{ currencyFormat(Exchange::read('lock_currency_have'), $payment['balance']) }}</strong> 
                        可用，您想要用您的账号余额支付该笔交易么？
                        </p>
                        <span>{{ Form::radio('payment_method', 'balance', Input::old('payment_method', TRUE)) }} 是</span>
                        <span class="ml20">{{ Form::radio('payment_method', 'deposit', Input::old('payment_method')) }} 否</span>
                    </div>                
                    @else
                    <div>
                        <input type="hidden" name="payment_method" value="deposit">
                    </div>
                    @endif
                @else
                    @if ($payment['pay'] > 0)
                    <div class="alert alert-danger">
                        抱歉，我们目前不支持该币种存款
                    </div>            
                    @else
                    <div class="hide">
                        <input type="radio" checked name="payment_method" value="balance">
                    </div>  
                    @endif  
                @endif
             
                <input type="hidden" name="need2pay_amount" value="{{ $payment['pay'] }}">
                <input type="hidden" name="total_amount" value="{{ $payment['total'] }}">
                <input type="hidden" name="deposit_currency" value="{{ Exchange::read('lock_currency_have') }}">

                <div class="mt30 hide" id="bank-select-div">
                    <h5>请选择 Anying 的收款行</h5>
                    <p class="tips">
                        您可以选择下面任何银行作为您的收款行。<br>为了能够更加快速、廉价的转账，我们建议您选择的收款行与您的汇款行一致
                    </p>
                    <div class="row mb20">
                        <div class="col-sm-4">
                            <select class="form-control bank-select" id="deposit-bank-select"> </select>
                            <input type="hidden" name="deposit_bank" value="">
                        </div>
                    </div>
                </div>

                <h5 style="width:60%;" class="mt30">
                    确认收款人信息
                </h5>
                <p class="tips">
                    请认真核对下面的信息
                </p>
                
            @if ($recipient)
                <div class="row mt20">
                    <div class="col-sm-5">
                        <p><strong>取款至</strong><br>{{ $recipient['account_name'] }}</p>
                        <p><strong>收款行信息</strong></p>
                        <dl class="dl-horizontal">
                            <dt>国家: </dt>
                            <dd>{{ $recipient['country'] }}</dd>
 
                            @if ($recipient['swift_code'])
                            <dt>SWIFT/BIC 码: </dt>
                            <dd>{{ $recipient['swift_code'] }} </dd>
                            @endif

                            <dt>银行名称: </dt>
                            <dd>{{ sprintf('%s %s', $recipient['bank_name'], $recipient['branch_name']) }}</dd>

                            @if ($recipient['account_bsb'])
                            <dt>
                                {{ (isset($recipient['country']) ? $recipient['country'] : $recipient['transfer_country']) == 'Australia' ? 'BSB：' : 'Sort Code：' }}
                            </dt>
                            <dd>{{ $recipient['account_bsb'] }}</dd>
                            @endif

                            <dt>
                                {{ (isset($recipient['country']) ? $recipient['country'] : $recipient['transfer_country']) == 'Germany' ? 'IBAN：' : '银行账号：' }}
                            </dt>
                            <dd>{{ $recipient['account_number'] }}</dd>

                            <dt>银行地址: </dt>
                            <dd>
                                @if (isset($recipient['bank_street_number']))
                                    {{ sprintf('%s %s %s %s %s %s', 
                                                            $recipient['bank_unit_number'],
                                                            $recipient['bank_street_number'],
                                                            $recipient['bank_street'],
                                                            $recipient['bank_city'],
                                                            $recipient['bank_state'],
                                                            $recipient['bank_postcode']
                                                            )
                                                 }}   
                                @else
                                    {{ sprintf('%s %s %s %s %s %s', 
                                                            $recipient['unit_number'],
                                                            $recipient['street_number'],
                                                            $recipient['street'],
                                                            $recipient['city'],
                                                            $recipient['state'],
                                                            $recipient['postcode']
                                                            )
                                                 }} 
                                @endif
                            <dd>
                        </dl>                   

                    </div>

                    <div class="col-sm-6">
                        <p><strong>收款人地址</strong></p>
                        <address>
                            {{ $recipient['address'] }}
                            @if ($recipient['address_2'])
                            <br>
                            {{ $recipient['address_2'] }}
                            @endif
                            @if ($recipient['address_3'])
                            <br>
                            {{ $recipient['address_3'] }}
                            @endif
                            <br>
                            {{ $recipient['postcode'] }}
                        </address>
                        <p><strong>联系电话</strong></p>
                        <adress>
                            {{ sprintf('+%s %s', $recipient['phone_code'], $recipient['phone'])}}<br>
                        </adress>

                        <p><strong>取款原因</strong></p>
                        <address>
                            {{ Receipt::session('reason') }}
                            @if (Receipt::session('message'))
                                <br>
                                {{ Receipt::session('message') }} (留言)
                            @endif
                        </address> 
                    </div>
                </div>
            @else
                <div>
                    <p><strong>取款至</strong><br>
                        您的 {{ Exchange::read('lock_currency_want') }} 余额
                    </p>
                </div>
            @endif
            </div>

            @if ($total_fee > 0)
            <hr>
            <div class="flat-panel">
            	<h5>选择如何支付手续费</h5>
            	<p>手续费总计 <strong>{{ currencyFormat($currency, $total_fee, TRUE) }}</strong></p>
            	<p>您可选择下面任一账户支付手续费</p>
                <input type="hidden" name="convert_tx_fee_amount" value=""> 
                <input type="hidden" name="convert_tx_fee_rate" value=""> 
				<select class="form-control " data-currency="{{$currency}}" data-total-fee="{{$total_fee}}"
                			 id="transaction-fee-select" name="convert_tx_fee_currency">
					<optgroup label="存款支付">	
						<option value="deposit" {{ empty($tx_fee_payable_balances) ? 'selected' : '' }}>
                            存款 {{ currencyFormat($currency, $total_fee, TRUE) }}
                        </option>
					</optgroup>
					<optgroup label="余额支付">
					@foreach ($tx_fee_payable_balances as $b)
						<option value="{{ $b->currency }}" 
								data-iconurl="{{ URL::asset('assets/img/flags/' . $b->currency) }}.png"{{ $currency == $b->currency ? ' selected' : '' }}>
								{{ sprintf('%s (可用余额 %s)', $b->currency, currencyFormat($b->currency, $b->available_amount)) }}
						</option>
					@endforeach
					</optgroup>
				</select>
            </div>
            @endif

            <hr>
            <div class="flat-panel">
                <h5>交易信息</h5>
                <table class="table deal-detail-table">
                    <thead>
                        <tr>
                            <td colspan="3">
                                您用 {{ currencyFormat($currency, Exchange::read('lock_amount'), TRUE) }} 兑换
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>手持货币金额</td>
                            <td>锁定汇率</td>
                            <td>购买货币金额</td>
                        </tr>
                        <tr>
                            <td>{{ currencyFormat($currency, Exchange::read('lock_amount'), TRUE) }}</td>
                            <td>{{ sprintf('1 %s = %s %s', $currency, Exchange::read('lock_rate'), Exchange::read('lock_currency_want')) }}</td>
                            <td>{{ currencyFormat(Exchange::read('lock_currency_want'), Exchange::read('lock_amount') * Exchange::read('lock_rate'), TRUE) }}</td>
                        </tr>
                    @if ($total_fee != 0)
                        <tr>
                            <td colspan="3">
                                <strong>手续费: </strong>
                                {{ currencyFormat($currency, $total_fee, TRUE) }}
                            <span id="tx-fee-text"></span> 
                            </td>
                        </tr>                        
                    @endif
                    </tbody>
                    <!--
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <div class="text-right{{ $payment['deduct'] <= 0 ? ' hidden' : '' }}" id="balance_amount">
                                    <span class="amount-title">From Balance：</span>
                                    <span class="amount">{{ currencyFormat(Exchange::read('lock_currency_have'), $payment['deduct'], TRUE) }}</span><br>
                                    <span class="amount-title">Need To Pay：</span>
                                    <span class="amount">{{ currencyFormat(Exchange::read('lock_currency_have'), $payment['pay'], TRUE) }}</span>
                                </div>
                                <div class="text-right{{ $payment['balance'] > 0 ? ' hidden' : '' }}" id="deposit_amount">
                                    <strong>Need To Pay：</strong> 
                                    <span class="amount">{{ currencyFormat(Exchange::read('lock_currency_have'), $payment['total'], TRUE) }}</span>                                                           
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    -->
                </table>

            </div>
        @if (Session::has('sms_pin'))
            <hr>
            <div id="sms-pannel">
                <h5>安全短信验证</h5>
                <p id="get-sms-text">为了确保您的交易安全，请先获取短信验证码，输入正确后才能继续</p>
                <div id="confirm-sms-text">
                    <p class="help-block">短信验证码已经发送到您的手机 <span class="mobile"></span></p>
                </div>
                <div>
                    <div class="clearfix">
                        <div class="pull-left" style="width:250px;">
                            <strong>步骤 1</strong> - 获取短信验证码 
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-primary" id="get-pin-btn">获取短信验证码</a>
                        </div>
                    </div>
                    <div class="mt20 clearfix">
                        <div class="pull-left" style="width:250px;">
                        <strong>步骤 2</strong> - 输入短信验证码
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="sms_pin" id="sms_pin" placeholder="输入短信验证码" value="{{ Input::old('sms_pin') }}"> 
                        </div>
                    </div>                    
                </div>
                <hr>
            </div>
        @endif
            <div class="text-center mt30">
                {{ link_to('/lockrate/money', '上一步') }} &nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-lg">确认提交</button> 或 {{ link_to('/lockrate', '取消') }}                                            
            </div>
        </div>
    </div>
    {{ Form::close() }}
@stop

@section('inline-js')
{{ HTML::script('assets/js/selectize.js') }}
{{ HTML::script('assets/js/lib/lock-rate-confirm.js') }}
{{ HTML::script('assets/js/lib/transaction-fee.js') }}
{{ HTML::script('assets/js/lib/get-pin.js') }}
@stop
