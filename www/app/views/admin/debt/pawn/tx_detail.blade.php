@section('title')
借款项目详情
@stop

@section('content')
@include('admin.debt.subnav')

<div class="container">       
    <div class="white-box-body all-radius">

        @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
        @endif 
        
	    @if (Session::has('message'))
	    <div class="alert alert-success">
	        {{ Session::get('message') }}
	    </div>
	    @endif 
    
        <h4 class="mt0">借款项目详情  
        	<small class="ml10"><a href="{{ url('/admin/debt/pawn-detail-edit/'. $user_tx->id) }}">编辑</a></small>
        </h4>

        <div class="row" >
            <div class="col-sm-9 pull-left">
                <div class="flat-panel receipt-item-panel mt20">   
                    
                    <div class="row">
                    	<div class="col-sm-6">
							<dl class="dl-horizontal">
		                        <dt>借款标题</dt>
		                    	<dd>{{ $user_tx->title }}</dd> 
		                    	<dt>借款用户</dt>
		                    	<dd>{{ $user_tx->user_name }}</dd> 
		                    	<dt>流水号</dt>
		                    	<dd>{{ $user_tx->receipt_number }}</dd>
		                    	 <dt>有效时间</dt>
		                    	<dd>{{ $tx_config['valid_day_cnt'] }} 天</dd>
		                    	<dt>借款金额</dt>
		                    	<dd>{{ currencyFormat($user_tx->total_amount) }}</dd>
		                    	<dt>借款期限</dt>
		                    	<dd>{{ $user_tx->period_month }} 个月</dd>
		                        <dt>年化利率 </dt>
		                        <dd>{{ $user_tx->rate }}%</dd>
		                    	<dt>还款方式</dt>
		                    	<dd>{{ Repayment::desc($user_tx->repayment_type) }}</dd>
		                    	<dt>冻结保证金</dt>
		                    	<dd>{{ currencyFormat($user_tx->frozen_amount) }}</dd>
		                    	<dt>初期服务费</dt>
		                    	<dd>{{ currencyFormat($user_tx->fee_amount) }}</dd>
		                    	<dt>实收金额</dt>
		                    	<dd>{{ currencyFormat($user_tx->available_amount) }}</dd>
		                    	<dt>每月应还本息</dt>
		                    	<dd>{{ currencyFormat($user_tx->month_repayment_investor_amount) }}</dd>
		                    	<dt>每月管理费</dt>
		                    	<dd>{{ currencyFormat($user_tx->month_repayment_fee_amount) }}</dd>
		                    	<dt>每月还款总计</dt>
		                    	<dd>{{ currencyFormat($user_tx->month_repayment_total_amount) }}</dd> 
	                    	</dl> 
                    	</div>
                    	<div class="col-sm-6">
							<dl class="dl-horizontal">
		                    	<dt>公司名称</dt>
		                    	<dd>{{ $profile->company_name }}</dd>
		                    	<dt>公司行业</dt>
		                    	<dd>{{ $profile->industry }}</dd>
		                    	<dt>资金用途</dt>
		                    	<dd>{{ $profile->purpose }}</dd>
		                    	<dt>还款来源</dt>
		                    	<dd>{{ $profile->repayment_from }}</dd>
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
		                    	<dt>抵(质)押物</dt>
		                    	<dd>{{ $profile->pawn }}</dd>
	                    	</dl>
                    	</div>
                    </div>
                    
		            <div class="row">
                    	<div class="col-sm-12">
							<dl class="dl-horizontal">
		                    	<dt>借款描述</dt>
		                    	<dd>{{ $profile->desc }}</dd>
	                    	</dl> 
                    	</div>
		            </div>
		            <hr class="mb10">
		            <h4>认证信息
		            	<small class="ml10"><a href="{{ url('/admin/debt/pawn-material-add/'. $user_tx->id) }}">添加</a></small>
		            </h4>
		            <div class="row">
		                <div class="col-sm-8">
		                    <dl class="dl-horizontal">
		                    	@foreach ($materials as $material)
		                    	<dt>{{ $material->type }}</dt>
		                    	<dd>
		                    		<a target="_blank" href="/{{$material->path}}">{{ $material->name }}</a>
		                    		<a class="ml10 del-material" href="{{url('/admin/debt/del-material/'.$material->id)}}"><i class="fa fa-times orange"></i></a>
		                    	</dd>
		                    	@endforeach
		                    </dl> 
		                </div>
		            </div>
		            
                    <hr class="mb10">
		            <h4>投标详情</h4>
		            <div class="row">
		                <div class="col-sm-6">
		                    <dl class="dl-horizontal">
		                    	<dt>已借到金额</dt>
		                    	<dd>-</dd>
		                    </dl> 
		                </div>
		                <div class="col-sm-6">
		                    <dl class="dl-horizontal">
		                    	<dt>未借到金额</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>		                
		            </div>
		           	<div class="pannel-body table-responsive mt30">
		               	<table class="table table-hover table-striped" id="content-table">
		                    <thead>
		                        <tr>
		                        	<th>日期</th>
		                        </tr>
		                    </thead>
		                    <tbody>

								<tr>
									<td>-</td>
								</tr>

		                    </tbody>
		                </table>
		            </div>
                    <hr class="mb10">
		            <h4>还款详情</h4>
		            <div class="row">
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>借款总额</dt>
		                    	<dd>-</dd>
		                    </dl> 
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>应还总额</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>应还利息</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>		                		                
		            </div>
		            <div class="row">
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还总额</dt>
		                    	<dd>-</dd>
		                    </dl> 
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还本金</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还利息</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>		                		                
		            </div>
		            <div class="row">
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>未还总额</dt>
		                    	<dd>-</dd>
		                    </dl> 
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>未还本金</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>未还利息</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>		                		                
		            </div>
		            <div class="row">
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还正常还款费用</dt>
		                    	<dd>-</dd>
		                    </dl> 
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还提前还款费用</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>
		                <div class="col-sm-4">
		                    <dl class="dl-horizontal">
		                    	<dt>已还逾期还款费用</dt>
		                    	<dd>-</dd>
		                    </dl>
		                </div>		                		                
		            </div>		            		            		            
		           	<div class="pannel-body table-responsive mt30">
		               	<table class="table table-hover table-striped" id="content-table">
		                    <thead>
		                        <tr>
		                        	<th>日期</th>
		                        </tr>
		                    </thead>
		                    <tbody>

								<tr>
									<td>-</td>
								</tr>

		                    </tbody>
		                </table>
		            </div>		            		            
                    <hr class="mt30">
					@include('admin.customer.include_tx_detail_customer_info')					
				</div> 
			</div>
   
			<div class="col-sm-3">
				<div id="command-affix">				
					<div class="tx-detail-status label-{{ Tx::status_style($user_tx->status) }}">
						<p class="f20p">状态</p>
						<p> {{ Tx::status_desc($user_tx->status) }} </p>
					</div>
					@if ($user_tx->status == Tx::STATUS_FIRST_AUDITING)
					<h5>操作</h5>
					{{ Form::open(array('url' => url('/admin/debt/pawn-first-audit-succ', $user_tx->id), 'role' => 'form', 'id' => 'succFrm')) }}
					<p><button type="submit" class="tx-action-btn button button-rounded button-flat-primary succ-btn">初审通过</button></p>
					{{ Form::close() }}
					{{ Form::open(array('url' => url('/admin/debt/pawn-first-audit-fail', $user_tx->id), 'role' => 'form', 'id' => 'failFrm')) }}
					<p><button type="submit" class="tx-action-btn button button-rounded button-flat fail-btn">初审驳回</button></p>
					{{ Form::close() }}
					@endif
				</div>

            </div>
        </div>


    </div>
</div>

@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function() {
	$('#command-affix').affix({
		offset: {
			  top: 200, 
			  bottom: function () {
			        return (this.bottom = $('.footer').outerHeight(true))
			      }
			  }
	});
	
	init_confirm_modal('del-material', '确认要删除？'); 
	
	init_confirm_modal('clear-btn', '确定通过么？', 'submit', '#succFrm');
	init_confirm_modal('cancel-btn', '确定驳回么？', 'submit', '#failFrm');
	
});
</script>
@stop
