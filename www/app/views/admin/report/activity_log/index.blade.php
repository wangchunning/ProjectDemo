@section('title')
管理员操作日志
@stop

@section('content')
@include('admin.report.subnav')

<div class="container">
	<div class="white-box-body all-radius">
		<div class="flat-panel">
			
			<h4><i class="fa fa-users"></i> 管理员操作日志</h4>

			<hr class="mt20 mb20">

			{{ Form::open(array('url' => '/admin/report/activity-log', 'method' => 'get', 'role' => 'form', 'id' => 'frm', 'class' => 'form-horizontal')) }}
			@include('partials.table_search_group')
			{{ Form::close() }}

            <div class="pannel-body table-responsive mt30">
               <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                        	<th>日期</th>
                        	<th class="unsortable">操作人</th>
                            <th class="unsortable">模块</th> 
                            <th class="unsortable">类型</th>                          
                            <th class="unsortable">操作</th>
                            <th class="unsortable">操作内容</th>
                        </tr>
                    </thead>
                    <tbody>
						@foreach ($logs as $log)
						<tr>
							<td>{{ pretty_date($log->created_at) }}</td>
							<td>{{ $log->user_name }}</td>
							<td>{{ AdminActivityLog::category_desc($log->op_category) }}</td>
							<td>{{ AdminActivityLog::type_desc($log->op_type) }}</td>
							<td>{{ AdminActivityLog::operation_desc($log->operation) }}</td>
							<td>{{ $log->desc }}</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function(){
	
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


