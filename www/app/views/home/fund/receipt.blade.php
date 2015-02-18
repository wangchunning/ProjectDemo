@section('title')
存款收据
@stop

@section('content')
    @include('lockrate.subnav')
    @include('breadcrumb')
    <div class="container flat-panel">
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
                                 
                        <div class="row mb40">
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
                                    {{ currencyFormat($deposit['currency'], $deposit['amount'], TRUE) }}
                                </strong></div>
                            </div>
                        </div>
                        @if ( ! isset($bank_country) OR in_array('Australia', $bank_country))
                    	<hr class="noprint">	            
    		            <div class=" mb20 ml20 mr20 noprint">
    		                <p><strong>您想从网上银行转账么？</strong></p>
    		                <div class="row mt20">
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
        <div class="dashboard-body mb20">
            <div class="row">
                <div class="col-sm-9">
                    <h2 class="mb20"><i class="fa fa-credit-card"></i> 存款收据</h2>
                    
                    @if($receipt_obj->transaction('Deposit')->status != 'Waiting for fund')
                        {{ receiptTips($receipt['id']) }}
                    @endif

                    <div class="receipt-number mb10">
                        <div><i class="f20p fa fa-clock-o"></i> {{ time_to_tz($receipt['created_at']) }}</div>
                        <div class="receipt-id">收据编号: <span>{{ $receipt['id'] }}</span></div>
                    </div>    
                    <div class="flat-panel">
                        <hr class="mt10">                    
                        <h5 class="color-666"><b>存款到：</b></h5>   
                        <div>
                            您的 {{ currencyName($deposit['currency']) }} 账号
                        </div>
                        <hr>                                                                   
                    </div>
                    <div class="flat-panel mb20">
                        <h5 class="color-666">支付信息</h5>
                        <div class="row ml0 mr0 add-fund-detail">
                            <div class="col-xs-6">
                                总计金额: <strong><span class="highlight">{{ currencyFormat($deposit['currency'], $deposit['amount'], TRUE) }}</span></strong>
                            </div>
                            <div class="col-xs-6">
                                过期日期: <strong><span class="highlight">{{ Date('d/m/Y', strtotime($receipt['created_at']) + 172800) }}</span></strong>
                            </div>
                        </div>
                    </div>                 
                    @if (isset($tran_complete))
                    <div class="clearfix mt30">
                        <a class="pull-left btn btn-primary" href="/home">再一笔交易</a>
                        <a class="pull-right btn btn-primary" href="/balance/{{ $deposit['currency'] }}">查看我的余额</a>
                    </div>
                    @endif                 

                </div>
                @if($receipt_obj->transaction('Deposit')->status != 'Waiting for fund')
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
                @endif
            </div>  
 
                               
        </div>
    </div>
@stop
