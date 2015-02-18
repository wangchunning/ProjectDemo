@section('title')
确认借款信息 - 天添财富
@stop

@section('content')
@include('breadcrumb')

{{ Form::open(array('url' => '/debt/pawn-confirm', 'role' => 'form', 'id' => 'frm')) }}
<div class="container">
    <div class="dashboard-body">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif           

        <div class="flat-panel receipt-item-panel mt20">   
            <h4>确认借款信息</h4>
            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                        <dt>借款标题</dt>
                    	<dd>{{ $tx->title }}</dd>                    	
                    	<dt>借款金额</dt>
                    	<dd>{{ currencyFormat($tx->total_amount) }}</dd>
                    	<dt>借款期限</dt>
                    	<dd>{{ $tx->period_month }} 个月</dd>
                        <dt>年化利率 </dt>
                        <dd>{{ $tx->rate }}%</dd>
                    	<dt>还款方式</dt>
                    	<dd>{{ Repayment::desc($tx->repayment_type) }}</dd>
                    	<dt>冻结保证金</dt>
                    	<dd>{{ currencyFormat($tx->frozen_amount) }}</dd>
                    	<dt>初期服务费</dt>
                    	<dd>{{ currencyFormat($tx->fee_amount) }}</dd>
                    	<dt>实收金额</dt>
                    	<dd>{{ currencyFormat($tx->available_amount) }}</dd>
                    	<dt>借款描述</dt>
                    	<dd>{{ $profile->desc }}</dd>
                    </dl> 
                </div>
            </div>	
    
            <h4>每月还款信息</h4>
            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                    	<dt>每月应还本息</dt>
                    	<dd>{{ currencyFormat($tx->month_repayment_investor_amount) }}</dd>
                    	<dt>每月管理费</dt>
                    	<dd>{{ currencyFormat($tx->month_repayment_fee_amount) }}</dd>
                    	<dt>每月还款总计</dt>
                    	<dd>{{ currencyFormat($tx->month_repayment_total_amount) }}</dd>                    	
                    </dl> 
                </div>
            </div>
            
			<h4>公司信息</h4>
            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">
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
            <h4>认证信息</h4>
            <hr class="mb10">
            <div class="row">
                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                    	@foreach ($materials as $material)
                    	<dt>{{ Material::type_desc($material->type) }}</dt>
                    	<dd>
                    		<p><a target="_blank" href="/{{$material->path}}">{{ $material->name }}</a></p>
                    	</dd>
                    	@endforeach
                    </dl> 
                </div>
            </div>
	
			<hr class="mb20">
			
	        <div class="ml20">
	            <button type="submit" class="button button-rounded button-flat-primary">确认提交</button>                                       
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

});
</script>
@stop
