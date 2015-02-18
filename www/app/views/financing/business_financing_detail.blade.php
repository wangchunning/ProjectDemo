@section('title')
企业融资详细信息
@stop

@section('content')
@include('breadcrumb')

<div class="container">       
    <div class="flat-panel">

        <h4 class="mt0"> 企业融资详细信息 </h4>

        <div class="row">
            <div class="col-sm-9 pull-left">
                <div class="flat-panel asset-detail-panel mt20">   
                   
                    <dl class="dl-horizontal">
                        <dt>企业名称：</dt>
                        <dd>{{ $detail_obj->business_name }}</dd>
                        <dt>拟贷款金额: </dt>
                        <dd>{{ $detail_obj->expect_loan_amount }} 元</dd>
                        <dt>贷款申请期限: </dt>
                        <dd>{{ $detail_obj->loan_during }}</dd>
                        <dt>抵质押方式: </dt>
                        <dd>{{ $detail_obj->pledge }}</dd>
                        <dt>担保方式: </dt>
                        <dd>{{ $detail_obj->guarantee }}</dd>
                        <dt>营业执照: </dt>
                        <dd><a target="_blank" href="{{ url($detail_obj->license_file) }}">{{ $detail_obj->license_file }}</a></dd>
                        <dt>工商登记: </dt>
                        <dd><a target="_blank" href="{{ url($detail_obj->business_register_file) }}">{{ $detail_obj->business_register_file }}</a></dd>
                        <dt>税务登记: </dt>
                        <dd><a target="_blank" href="{{ url($detail_obj->tax_register_file) }}">{{ $detail_obj->tax_register_file }}</a></dd>
                        <dt>前3年经审计财务报表: </dt>
                        <dd><a target="_blank" href="{{ url($detail_obj->years_finance_report_file) }}">{{ $detail_obj->years_finance_report_file }}</a></dd>
                        <dt>近期财务报表: </dt>
                        <dd><a target="_blank" href="{{ url($detail_obj->recent_finance_report_file) }}">{{ $detail_obj->recent_finance_report_file }}</a></dd>
                        <dt>融资用途: </dt>
                        <dd>{{ $detail_obj->financing_purpose }}</dd>
                    </dl>
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

    });
</script>
@stop
