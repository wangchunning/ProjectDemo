

 		
 		<div class="row debt-detail-head">
 			<div class="col-sm-8 pt20">
 				<div class="row">
 					<div class="col-sm-5">
 						<label>标的总额（元）</label>
 						<div class="f28p">{{ currencyFormat($tx->total_amount) }}</div>
 					</div>
 					<div class="col-sm-4">
 						<label>年利率</label>
 						<div><span class="f28p">{{ $tx->rate }}</span><span class="f24p grey">%</span></div>
 					</div>
 					<div class="col-sm-3">
 						<label>还款期限（月）</label>
 						<div class="f28p">{{ $tx->period_month }}</div>
 					</div>
 				</div>
 				<div class="row mt30">
 					<div class="col-sm-5">
 						<div>
	                    	<span>担保范围</span>
	                    	<span class="ml20">{{ $profile->guarantee_range }}</span>

                    	</div>
                    	<div class="mt10">
	                    	<span>还款方式</span>
	                    	<span class="ml20">{{ Repayment::desc($tx->repayment_type) }} </span>
                    	</div>
 					</div>
 					<div class="col-sm-7">
 						<div>
	                    	<span>提前还款费率</span>
	                    	<span class="ml20">{{ $tx->repayment_in_advance_rate }}%</span>
                    	</div>
                    	<div class="mt10">
	                    	<span>月还款额 (元)</span>
	                    	<span class="ml20">{{ currencyFormat($tx->month_repayment_total_amount) }}</span>
                    	</div>
 					</div>
 				</div>
 				
  				<div class="row mt40">
  					@if ($tx->status == Tx::STATUS_LENDING)
  					<div class="col-sm-2">投标进度</div>
	                <div class="col-sm-5 pl0">
	                    <div class="ml20 progress">
	                    	<?php $_progress = round($tx->invest_amount / $tx->total_amount, 2) * 100 ?>
	                    	<div class="progress-bar" role="progressbar" aria-valuenow="{{ $_progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $_progress }}%;">
	                    	{{ $_progress }}%
	                    	</div>
	                    </div>
	                </div>
	                <div class="col-sm-5">
	                    <span>剩余时间</span>
                    </div>
                    @endif	
 				</div>		
 					 			
 			</div>
 			
 			@if ($tx->status == Tx::STATUS_LENDING)
 			<div class="col-sm-4 invest-panel pt20 pb20">
 			 	<div class="form-group">
 					<label>可投金额（元）</label>
 					<div class="f28p">{{ currencyFormat($tx->total_amount - $tx->invest_amount) }}</div>
 				</div>
 			 	<div class="">
 					<span>账户余额 {{ currencyFormat(empty($balance) ? 0 : $balance->available_amount) }}</span>
 					<span class="pull-right"><a target="_blank" href="/fund/add">充值</a></span>
 				</div> 
 				<div class="form-group row mt5">
 					<div class="col-sm-12">
 						<input type="text" name="invest_amount" class="form-control" placeholder="输入金额为50的倍数"
 							data-bv-notempty="true"
	                		data-bv-notempty-message="* 请输入投资金额"
	                		data-bv-between="true"
	                		data-bv-between-inclusive="true"
	                		data-bv-between-max="10000"
	                		data-bv-between-min="50"
	                		data-bv-between-message="* 可借投资范围50 ~ 10000"
	                		data-bv-regexp="true"
	                		pattern="(^[1-9]\d*(00|50)$)|(^50$)"
	                		data-bv-regexp-message="* 投资金额只能是 50 的整数倍"/>
	                		
 						<span class="form-control-feedback mr15">元</span>
 					</div>
 				</div>	
				<div class="mt30 row">
					<span class="col-sm-12">
					<button type="submit" class="button button-rounded button-flat-action ">投 标</button>        
					</span>	                                   
				</div>		
 			</div>
 			@else
 			<div class="col-sm-4 invest-panel pt20 pb20">
 			 	<div class="form-group">
 					<label>可投金额（元）</label>
 					<div class="f28p">￥0.00</div>
 				</div>
 			 	<div class="">
 					<span>账户余额 {{ currencyFormat(empty($balance) ? 0 : $balance->available_amount) }}</span>
 					<span class="pull-right"><a target="_blank" href="/fund/add">充值</a></span>
 				</div> 
 				<div class="form-group row mt5">
 					<div class="col-sm-12">
 						<input type="text" name="invest_amount" class="form-control" placeholder="{{Tx::status_desc($tx->status)}}" readonly/>
 					</div>
 				</div>	
				<div class="mt30 row">
					<span class="col-sm-12">
					<button type="submit" class="button button-rounded button-flat-action disabled">{{ Tx::status_desc($tx->status) }}</button>        
					</span>	                                   
				</div>		
 			</div> 			
 			@endif
 			
 		</div><!-- /head -->
