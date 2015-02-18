@section('title')
锁定汇率收据
@stop


@section('content')
    @include('lockrate.subnav')        
    @include('breadcrumb')
    <div class="container flat-panel">
    	<?php $fee_total = $receipt_obj->converted_fee_total ?>
        @if ($payment['pay'] > 0 OR $payment_method == 'deposit')
            @if($receipt_obj->transaction('Deposit')->status == 'Waiting for fund')
            <div class="dashboard-body mb20">
                <div class="row">
                	<!--left side -->
                    <div class="col-sm-9">
                    <h2 class="mb20">
                    	<i class="fa fa-money noprint"></i> 
                        <strong class="noprint">下一步需要完成</strong>
                        @if (is_business_member_login())
                            <strong class="visible-print">
                                <?php $user = real_user(); ?>
                                {{ $user->business->business_name }}
                            </strong>
                        @endif
                    </h2>
                    {{ receiptTips($receipt['id']) }}  
                    <h2 class="mt30 mb10"><strong>存款到 Anying 账号</strong></h2>
                    <p class="tips">
                        请您尽快存款到下面的 Anying 账号。<br>一旦收到了您的汇款，我们就会尽快的完成您的交易
                    </p>
                    <div class="payment-panel-border-top"></div>
                    <div class="payment-panel">   
                        <div class="row">
                            <div class="col-xs-8 bankinfo">
                                @if ($bankinfo = $receipt_obj->transaction('Deposit')->bank)
                                    @foreach ($bankinfo as $bank)
                                    <?php $bank_country[] = $bank->country; ?>
                                    <p> {{ bankInfo($bank->id, $deposit['currency'], $bank->amount) }} </p>
                                    @endforeach
                                @else
                                <p><div class="mt20 f16p">您的交易已经提交成功！ </div> </p>
                                <p><div class="f16p">我们会尽快联系您，告知您的收款行信息</div> </p>
                                <p><div class="f16p">给您带来不便请您谅解</div> </p>
                                @endif
                            </div>
                            <div class="col-xs-3 need2pay">
                                <div class="label-primary white need2pay-title">
                                    <p class="ml10 f20p"><strong>需要支付</strong></p>
                                </div>
                                <div class="need2pay-amount"><strong>                            
                                    {{ $payment_method == 'deposit' ? currencyFormat($deposit['currency'], $deposit['amount'], TRUE) : currencyFormat($fx['lock_currency_have'], $payment['pay'], TRUE) }}
                                    
                                </strong>
                                
                                @if ($fee_total['convert_fee_currency'] == $deposit['currency'] &&
                                		$fee_total['convert_fee_amount'] != 0)
                                <p class="f12p">(including {{ currencyFormat($fee_total['convert_fee_currency'], $fee_total['convert_fee_amount'], TRUE) }} fee)</p>
                                @endif
                                </div>
                            </div>
                        </div>
                        @if ( ! isset($bank_country) OR in_array('Australia', $bank_country))
                        <hr class="noprint">
			            <div class="mt40 mb20 noprint">
			                <p><strong>您想从网上银行转账么？</strong></p>
			                <div class="row mt40">
			                    <div class="col-sm-3 text-center">
			                        <a href="https://online.westpac.com.au/esis/Login/SrvPage?referrer=http://www.westpac.com.au/HomepageAlternative" class="thumbnail" target="_blank"><img src="/assets/img/ebank_1.png" height="40"></a>
			                    </div>
			                    <div class="col-sm-3 text-center">
			                        <a href="https://www.commbank.com.au/personal/login-netbank.html" class="thumbnail" target="_blank"><img src="/assets/img/ebank_2.png" height="40"></a>
			                    </div>
			                    <div class="col-sm-3 text-center">
			                        <a href="https://www.anz.com/INETBANK/bankmain.asp" class="thumbnail" target="_blank"><img src="/assets/img/ebank_3.png" height="40"></a>
			                    </div>
			                    <div class="col-sm-3 text-center">
			                        <a href="https://ib.nab.com.au/nabib/index.jsp" class="thumbnail" target="_blank"><img src="/assets/img/ebank_4.png" height="40"></a>                     
			                    </div>                    
			                </div>
			            </div>
                        @endif               
                    </div>  <!-- /payment-panel -->
                    <div class="payment-panel-border-bottom"></div>   
                    </div><!-- /left side - col-sm-9 --> 
                    <!-- right side -->
                    <div class="col-sm-3 noprint mt20">
                    <?php $tran = $receipt_obj->transaction('Deposit'); ?>
                    @if ($tran AND $tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                        <div class="receipt-status label-danger">
                            <p class="f20p">状态</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                        <p class="red tcenter">{{ overdueCheck($tran->created_at) }}</p>
                    @else
                        <div class="receipt-status label-{{ receiptStatusStyle($receipt['status']) }}">
                            <p class="f20p">状态</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                    @endif
                        <div style="text-align:center;" class="mt10">
                            <span class="fa fa-print"></span> <a href="javascript:;" onclick="javascript:window.print();">打印收据</a>
                        </div>
                    </div><!-- /right side -->                   
    			</div><!-- /first page --> 
    		</div>
            @endif
        @endif 
            <!-- 2nd page -->
		<div class="dashboard-body mb20 ">
            <div class="row">
            	<!--left side -->
                <div class="col-sm-9">            
                    <h2 class="mb20"><i class="fa fa-credit-card"></i>交易收据</h2>
                    <!-- receipt tips -->
                    @if( ! isset($receipt_obj->transaction('Deposit')->status) OR $receipt_obj->transaction('Deposit')->status != 'Waiting for fund')
                        {{ receiptTips($receipt['id']) }} 
                    @endif
                    <!-- /receipt tips -->
                    <div class="receipt-number mb10">
                        <div><i class="f20p fa fa-clock-o"></i> {{ time_to_tz($receipt['created_at']) }}</div>
                        <div class="receipt-id">收据编号: <span>{{ $receipt['id'] }}</span></div>
                    </div>        
                    <div class="flat-panel">
 
                    	<hr class="mt10"> 
						@if ($recipient) 
                    	<!-- transfer detail -->   
                    	<div class="row receipt-row">   
	                    	<div class="col-xs-3">               
	                        	<strong>取款至：</strong>
	                        </div>                        
                            <div class="col-xs-5">
                            	<div ><p><strong>{{ $recipient['transfer_country'] }}</strong></p></div>
                                <p><strong>{{ $recipient['account_name'] }}</strong></p>
                                <address>
                                {{ $recipient['address'] }}<br>

                                @if ($recipient['address_2'])
                                {{ $recipient['address_2'] }}<br>
                                @endif

                                @if (isset($recipient['address_3']) AND $recipient['address_3'])                        
                                {{ $recipient['address_3'] }}<br>
                                @endif

                                {{ $recipient['transfer_country'] }}<br>
                                {{ $recipient['postcode'] }}
                                </address>                                                
                            </div>
                            <div class="col-xs-4">
                            	<address class="mt30">
                            		<strong>联系电话</strong><br>
                            		{{ sprintf('+%s %s', $recipient['phone_code'], $recipient['phone'])}}
                            	</address>
                            	<address>
                            		<strong>Email</strong><br>
               	             		-
               	             	</address>
               	             	
                            </div>
                        </div><!--/row transfer detail-->  
                   		<hr class="mt10"> 
                    	<!-- bank detail -->   
                    	<div class="row">   
	                    	<div class="col-xs-3">               
	                        	<strong>收款行信息</strong>
	                        </div>                       
                            <div class="col-xs-9">
                                <p>
                                	<strong>
                                	{{ sprintf('%s - %s', $recipient['bank_name'], $recipient['branch_name']) }}
                                	</strong>
	                                @if ($recipient['swift_code'])
	                                <font class="grey">(SWIFT/BIC 码: {{ $recipient['swift_code'] }} )</font>
	                                @endif                                	
                                </p>
                                @if (isset($recipient['country']))
                                <p>
                                    <font class="grey">国家: </font><span class="ml15">{{ $recipient['country'] }}</span>
                                </p>
                                @endif

                                @if (isset($recipient['bank_street_number']))
                                <p>
                                    <font class="grey">银行地址: </font>
                                    <span class="ml15">
                                    {{ sprintf('%s %s %s %s %s %s', 
                                                        $recipient['bank_unit_number'],
                                                        $recipient['bank_street_number'],
                                                        $recipient['bank_street'],
                                                        $recipient['bank_city'],
                                                        $recipient['bank_state'],
                                                        $recipient['bank_postcode']
                                                        ) }}
                                    </span>
                                </p>
                                @endif

                                <p>
                                @if ($recipient['account_bsb'])
                                    <font class="grey">
                                    {{ sprintf('%s & 银行账号', (isset($recipient['country']) ? $recipient['country'] : $recipient['transfer_country']) == 'Australia' ? 'BSB' : 'Sort Code') }}
                                    </font>
                                    <span class="ml15">{{ sprintf("%s %s", $recipient['account_bsb'], $recipient['account_number']) }}</span>
                                @else
                                	<font class="grey">银行账号</font>
                                	<span class="ml15">{{ $recipient['account_number'] }}</span>
                                @endif
                                </p>                                                 
                            </div>
                        </div><!--/row bank detail-->      
                   		<hr class="mt10"> 
                    	<!-- reason -->   
                    	<div class="row">   
	                    	<div class="col-xs-3">               
	                        	<strong>取款原因</strong>
	                        </div>                       
                            <div class="col-xs-9">
                                <p>{{ $reason }}</p>
                                @if ($message)
                                    {{ $message }} (留言)
                                @endif
                                </p>
                            </strong>                                                  
                            </div>
                        </div><!--/row reason -->                                                 
                		@else
                       	<!-- no recipient -->
                    	<div class="row receipt-row">   
	                    	<div class="col-xs-3">               
	                        	<strong>取款到：</strong>
	                        </div>                        
                            <div class="col-xs-5">
								您的 {{ $fx['lock_currency_want'] }} 账号                                             
                            </div>
                        </div>                        
                		@endif

                        <hr>                                                                   
                    </div><!-- /flat-panel -->
                    <h2 class="mb20">交易信息</h2>
                    <div class="flat-panel">
                        <div class="deal-detail mb20">
                            <div class="deal-detail-title">
                            	您用 
                            	<strong>{{ currencyFormat($fx['lock_currency_have'], $fx['lock_amount'], TRUE) }}</strong>兑换
                            </div>
                            <div class="row ml10 mt10">
                            	<div class="col-xs-3">兑换金额：</div>
                            	<div class="col-xs-9">
                            		{{ currencyFormat($fx['lock_currency_have'], $fx['lock_amount'], TRUE) }}
                            		{{ currencyFlag($fx['lock_currency_have']) }} =
                            		{{ currencyFormat($fx['lock_currency_want'], $fx['lock_amount'] * $fx['lock_rate'], TRUE) }}
                            		{{ currencyFlag($fx['lock_currency_want']) }}
                            	</div>
                            </div>
                            <div class="row ml10 mt10">
                            	<div class="col-xs-3">锁定汇率：</div>
                            	<div class="col-xs-9">
                                    {{ sprintf('1 %s = <strong>%s</strong> %s', $fx['lock_currency_have'], $fx['lock_rate'], $fx['lock_currency_want']) }}
                            	</div>
                            </div>
                            <div class="row ml10 mt10 mb10">
                                <div class="col-xs-3">手续费:</div>
                                <div class="col-xs-8">
                                    {{ currencyFormat($fee_total['convert_fee_currency'], $fee_total['convert_fee_amount'], TRUE) }}
                                </div>                        	                            	
                            </div>
                            <div class="deal-detail-footer">
                                <p class="notes mb0 text-right"><b>*提示:</b> 请在 48 小时内完成交易</p> 
                            </div><!--/deal-detail-footer-->

                        </div>
                        
                    </div><!--/transaction detail-->
                    @if (isset($tran_complete))
                    <hr>
                    <div class="clearfix noprint">
                        <a class="pull-left btn btn-primary" href="/home">再一笔交易</a>
                        <a class="pull-right btn btn-primary" href="/balance/{{ $fx['lock_currency_have'] }}">查看我的余额</a>
                    </div>
                    @endif
				</div><!--/left side-->
                @if( ! isset($receipt_obj->transaction('Deposit')->status) OR $receipt_obj->transaction('Deposit')->status != 'Waiting for fund')                    <!-- right side -->
                    <div class="col-sm-3 noprint mt20">
                    <?php $tran = $receipt_obj->transaction('Deposit'); ?>
                    @if ($tran AND $tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                        <div class="receipt-status label-danger">
                            <p class="f20p">状态</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                        <p class="red tcenter">{{ overdueCheck($tran->created_at) }}</p>
                    @else
                        <div class="receipt-status label-{{ receiptStatusStyle($receipt['status']) }}">
                            <p class="f20p">状态</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                    @endif
                        <div style="text-align:center;" class="mt10">
                            <span class="fa fa-print"></span> <a href="javascript:;" onclick="javascript:window.print();">打印收据</a>
                        </div>
                    </div><!-- /right side --> 
                @endif
			</div><!--/row-->

     
		</div> <!--/2nd page-->           

    </div><!--/container-->
@stop
