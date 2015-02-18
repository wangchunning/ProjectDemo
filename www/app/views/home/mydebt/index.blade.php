@section('title')
我的借款记录
@stop

@section('content')
@include('home.subnav')

<div class="container">
	<div class="white-box-body all-radius">
		<div class="flat-panel">
			
			<h4><i class="fa fa-users"></i> 借款记录</h4>

			<hr class="mt20 mb20">

			{{ Form::open(array('url' => '/mydebt', 'method' => 'get', 'role' => 'form', 'id' => 'frm', 'class' => 'form-horizontal')) }}
			@include('partials.table_search_group')
			{{ Form::close() }}

            <div class="pannel-body table-responsive mt30">
				@include('home.mydebt.debt_list')
                {{ $txs->links() }}
            </div>
        </div>
    </div>
@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function(){

    $('select').selectBoxIt({
		theme: 'bootstrap',
		autoWidth: false
    });

    $(".datepicker").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language:'zh-CN',
        minView: 2,
        viewSelect: 2
    });

	init_data_table('content-table', false, false, '');
	
	
})
</script>
@stop


