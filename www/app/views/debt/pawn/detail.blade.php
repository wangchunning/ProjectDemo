@section('title')
借贷详情 - 天添财富
@stop

@section('content')
@include('breadcrumb')

{{ Form::open(array('url' => '/invest/invest-add/'.$tx->id, 'role' => 'form', 'id' => 'frm')) }}
<div class="container flat-panel">
    
    <div class="dashboard-body pt10 mb20">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif           

		<h4 class="f24p">
			{{ $tx->title }}
			<span class="pull-right f14p mt10"><a target="_blank" href="#">借款协议（范本）</a></span>
		</h4>
		
 		<hr class="mb10">
 		@include('debt.detail_head')


 		<div class="debt-detail-content">
			<ul class="nav nav-tabs" role="tablist" id="nav-tab">
				<li role="presentation" class="active"><a href="#info" role="tab" data-toggle="tab">标的详情</a></li>
				<li role="presentation"><a href="#investor" role="tab" data-toggle="tab">投标记录</a></li>
				<li role="presentation"><a href="#repayment" role="tab" data-toggle="tab">还款表现</a></li>
			</ul>
			
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="info">
					
					<div class="flat-panel receipt-item-panel mt20">   
			            <h4 class="mt40 mb20">项目概述</h4>
			            <div class="row">
			                <div class="col-sm-8">
			                    <p>{{ $profile->desc }}</p>
			                </div>
			            </div>	
			            <hr>
			            <h4>
			            	信用档案
			            	
			            </h4>
			            <div class="row">
			                <div class="col-sm-6">
			                    <dl class="dl-horizontal">
			                    	<dt class="mt5">信用等级</dt>
			                    	<dd>
			                    		<span class="badge badge-{{CreditLevel::color($user->credit_level)}}">
			            					{{ CreditLevel::desc($user->credit_level) }}
			            				</span>
			            			</dd>
			                    	<dt>逾期金额</dt>
			                    	<dd>￥-</dd>
			                    	<dt>逾期次数</dt>
			                    	<dd>-笔</dd>

			                    </dl> 
			                </div>
			                <div class="col-sm-6">
			                    <dl class="dl-horizontal">
			            			<dt>申请借款</dt>
			                    	<dd>-笔</dd>
			                  		<dt>成功借款</dt>
			                    	<dd>-笔</dd>
			                    	<dt> 还清笔数</dt>
			                    	<dd>-笔</dd>
			                    </dl> 
			                </div>
			            </div>			            
			            <hr>
			            <h4>融资方信息</h4>
			            <div class="row">
			                <div class="col-sm-6">
			                    <dl class="dl-horizontal">
			                    	<dt>公司名称</dt>
			                    	<dd>{{ $profile->company_name }}</dd>
			                    	<dt>资金用途</dt>
			                    	<dd>{{ $profile->purpose }}</dd>
			                    	<dt>还款来源</dt>
			                    	<dd>{{ $profile->repayment_from }}</dd>
			                    	<dt>经营情况</dt>
			                    	<dd>{{ $profile->operation_info }}</dd>
			                    	<dt>涉诉情况</dt>
			                    	<dd>{{ $profile->break_law_info }}</dd>
			                    	<dt>征信情况</dt>
			                    	<dd>{{ $profile->credit_info }}</dd>
			                    </dl> 
			                </div>
			                <div class="col-sm-6">
			                    <dl class="dl-horizontal">
			                    	<dt>公司行业</dt>
			                    	<dd>{{ $profile->industry }}</dd>
			                    	<dt>所在地</dt>
			                    	<dd>{{ $profile->state.' '.$profile->city }}</dd>
			                    	<dt>注册日期</dt>
			                    	<dd>{{ $profile->register_date }}</dd>
			                    	<dt>注册资本</dt>
			                    	<dd>{{ $profile->register_capital }} 万元</dd>
			                    	<dt>资产净值</dt>
			                    	<dd>{{ $profile->property_value }} 万元</dd>
			                    	<dt>上季度经营收入</dt>
			                    	<dd>{{ $profile->last_year_income }} 万元</dd>
			                    </dl> 
			                </div>
			            </div>
			            
			            <hr class="mb10">
			            <h4>担保资料</h4>
			            <div class="row">
			                <div class="col-sm-8">
			                    <dl class="dl-horizontal">
			                    	<dt>担保范围</dt>
			                    	<dd>{{ $profile->guarantee_range }}</dd>
			                    	<dt>抵(质)押物</dt>
			                    	<dd>{{ $profile->pawn }}</dd>
			                    	<dt>担保情况</dt>
			                    	<dd>{{ $profile->guarantee_info }}</dd>
			                    </dl> 
			                </div>
			            </div>
			            <hr class="mb10">
			            <h4>认证信息</h4>
			            <div class="row">
			                <div class="col-sm-12 text-center">
								<div class="table-responsive  pl40 pr40 mt20">
									<table class="table table-hover table-striped f14p">
					                    <thead>
					                        <tr>
					                        	<th class="text-center">认证项目</th>
					                            <th class="text-center">认证状态</th> 
					                            <th class="text-center">通过日期</th> 
					                        </tr>
					                    </thead>
					                    <tbody>
					                    	@foreach ($approved_materials as $type => $date)
					                    	<tr>
				                    			<td>{{ $type }}</td>
				                    			<td><i class="fa fa-check-circle lightblue f16p"></i></td>
				                    			<td>{{ $date }}</td>
			                    			</tr>
			                    			@endforeach
					                    </tbody>
									</table>
								</div>			                    		                    	
			                </div>
			            </div>			          
					</div><!-- /detail -->
				</div><!-- /info panel -->
				
				<div role="tabpanel" class="tab-pane" id="investor">...</div>
				<div role="tabpanel" class="tab-pane" id="repayment">...</div>
			</div>
 		
 		</div>
	</div>       
</div>
{{ Form::close() }}
@stop

@section('inline-js')

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });
    
    $('#nav-tab a').click(function (e) {
    	e.preventDefault();
    	$(this).tab('show');
    })
});
</script>
@stop
