@section('title')
取款收据
@stop

@section('content')
@include('lockrate.subnav')
@include('breadcrumb')
<div class="container flat-panel">
    <div class="dashboard-body mb20">
        <div class="row">
            <div class="col-sm-9">
                <h2 class="mb20">
                	<i class="fa fa-credit-card noprint"></i> 
                        <strong class="noprint">取款收据</strong>
                        @if (is_business_member_login())
                            <strong class="visible-print">
                                <?php $user = real_user(); ?>
                                {{ $user->business->business_name }}
                            </strong>
                        @endif
                </h2>
                <div class="receipt-number mb10">
                    <div><i class="f20p fa fa-clock-o"></i> {{ time_to_tz($receipt['created_at']) }}</div>
                    <div class="receipt-id">收据编号: <span>{{ $receipt['id'] }}</span></div>
                </div>
                {{ receiptTips($receipt['id']) }}  
                <!-- single withdraw -->
	            @if (count($data) == 1)
	            <?php $recipient = $data[0]['recipient']; ?>
                <div class="flat-panel recepit-item-panel">
	                <hr class="mt10">
	            	<div class="row receipt-row">   
	                	<div class="col-xs-3">               
	                    	<strong>取款至</strong>
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
	                                                    ) 
	                             }}
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
	                        <p>{{ $data[0]['reason'] }}</p>
	                        @if ($data[0]['message'])
	                            {{ $data[0]['message'] }} (留言)
	                        @endif
	                        </p>
	                    </strong>                                                  
	                    </div>
	                </div><!--/row reason -->                                                                     
                </div>
                @else <!--/if withdraw == 1 -->
            
            	<!-- multi withdraw -->
               	@foreach ($data as $d) 
               	<hr class="mt10">
               	<div class="flat-panel receipt-item-panel">
	                <div class="row mt20">
	                    <div class="col-xs-6">
	                        <p><strong>取款至</strong><br>{{ $d['recipient']['account_name'] }}</p>
	                        <p><strong>收款行信息</strong></p>
                            <div class="row mt5">
                            	<div class="col-xs-4">国家: </div>
                            	<div class="col-xs-8">{{ $d['recipient']['country'] }}</div>
							</div>
		                    @if ($d['recipient']['swift_code'])
		                    <div class="row mt5">
		                        <div class="col-xs-4">SWIFT/BIC 码: </div>
		                        <div class="col-xs-8">{{ $d['recipient']['swift_code'] }} </div> 
		                    </div>
		                    @endif
							<div class="row mt5">
		                        <div class="col-xs-4">银行名称: </div>
		                        <div class="col-xs-8">{{ sprintf('%s %s', $d['recipient']['bank_name'], $d['recipient']['branch_name']) }}</div>
							</div>
							<div class="row mt5">
		                        <div class="col-xs-4">银行账号: </div>
		                        <div class="col-xs-8">{{ sprintf('%s %s', $d['recipient']['account_bsb'], $d['recipient']['account_number']) }}</div>
							</div>
							<div class="row mt5">
		                        <div class="col-xs-4">银行地址: </div>
		                        <div class="col-xs-8">
	                            	@if (isset($d['recipient']['bank_street_number']))
	                            	{{ sprintf('%s %s %s %s %s %s', 
	                                                        $d['recipient']['bank_unit_number'],
	                                                        $d['recipient']['bank_street_number'],
	                                                        $d['recipient']['bank_street'],
	                                                        $d['recipient']['bank_city'],
	                                                        $d['recipient']['bank_state'],
	                                                        $d['recipient']['bank_postcode']
	                                                        )
	                                             }}   
	                            	@else
	                            	{{ sprintf('%s %s %s %s %s %s', 
	                                                        $d['recipient']['unit_number'],
	                                                        $d['recipient']['street_number'],
	                                                        $d['recipient']['street'],
	                                                        $d['recipient']['city'],
	                                                        $d['recipient']['state'],
	                                                        $d['recipient']['postcode']
	                                                        )
	                                             }} 
	                            	@endif			                        	
		                        </div>							
                            </div>                
	                    </div>
	                    <div class="col-xs-3">
	                        <p><strong>收款人地址</strong></p>
	                        <address>
	                            {{ $d['recipient']['address'] }}
	                        @if ($d['recipient']['address_2'])
	                            <br>
	                            {{ $d['recipient']['address_2'] }}
	                        @endif
	                        @if ($d['recipient']['address_3'])
	                            <br>
	                            {{ $d['recipient']['address_3'] }}
	                        @endif
	                            <br>
	                            {{ $d['recipient']['postcode'] }}
	                        </address>

	                        <p><strong>联系电话</strong></p>
	                        <address>
	                        	{{ sprintf('+%s %s', $d['recipient']['phone_code'], $d['recipient']['phone'])}}
	                        </address>
	                        <p><strong>取款原因</strong></p>
	                        <address>
	                            {{ $d['reason'] }}
	                        	@if ($d['message'])
	                           		<br>
	                            	{{ $d['message'] }} (留言)
	                        	@endif
	                        </address>                       
	                    </div>
	                    <div class="col-xs-3">
	                    	<p>
	                    		<span class="grey"><strong>金额</strong></span><br>
	                    		<span class="f20p">{{ currencyFormat($currency, $d['amount'], TRUE) }}</span>
	                    	</p>
	                    	<p>
	                    		<span class="grey"><strong>手续费</strong></span><br>
	                    		<span class="f20p">{{ currencyFormat($currency, $d['fee']['total'], TRUE) }}</span>
	                    	</p>                    	
	                    </div>
	                </div>
	            </div>
                @endforeach
                @endif

	            <hr>
	            <div class="flat-panel">
	                <h5>总计</h5>
                    <div class="deal-detail mb20">
                        <div class="deal-detail-title">
                        	从您的 Anying 账号取款 
                        </div>
                        <div class="row ml10 mt10">
                        	<div class="col-xs-4">金额总计:</div>
                        	<div class="col-xs-8">
                        		{{ $receipt_obj->amount }}
                        	</div>
                        </div>
                        <div class="row mt10 ml10 mb10">
                        	<?php  
                        		$fee_total = $receipt_obj->fee_total; 
                        		$convert_fee_arr = $receipt_obj->converted_fee_total;
                        	?>
                            <div class="col-xs-4">手续费总计: </div>
                            <div class="col-xs-8">
                            	{{ currencyFormat($fee_total['currency'], $fee_total['amount'], TRUE) }}
                            	@if ($fee_total['currency'] != $convert_fee_arr['convert_fee_currency'])
                            	({{ currencyFormat($convert_fee_arr['convert_fee_currency'],
                            						$convert_fee_arr['convert_fee_amount'], true) }})
                            	@endif
                            </div>                                                                            	                            	
                        </div>
                        <div class="deal-detail-footer">
	                        <div class="text-right">
	                            <span class="">共计 {{ count($data) }} 笔交易</span>
	                        </div>
                        </div>

                    </div>
                </div>	            

		        @if ($tran_complete)

		        <div class="clearfix mt30">
		            <a class="pull-left btn btn-primary" href="/home">再一笔交易</a>
		            <a class="pull-right btn btn-primary" href="/balance/{{ $currency }}">查看我的余额</a>
		        </div>
		        @endif 

            </div> <!--/ left -->

            <div class="col-sm-3 noprint mt20">
                <div class="receipt-status label-{{ receiptStatusStyle($receipt['status']) }}">
                    <p class="f20p">状态</p>
                    <p>{{ $receipt['status'] }}</p>
                </div>
                <div style="text-align:center;" class="mt10">
                    <span class="fa fa-print"></span> <a href="javascript:;" onclick="javascript:window.print();">打印收据</a>
                </div>
            </div>
        </div>


    </div>
</div>
@stop
