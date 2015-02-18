@section('title')
信用等级管理
@stop

@section('content')

<div class="container">
	<div class="flat-panel white-box-body all-radius">
		
		@if ($errors->has() OR Session::has('error'))
		<div class="alert alert-danger">
			{{ $errors->has() ? $errors->first() : Session::get('error') }}
		</div>
		@endif 
		
		<h4 class="mt0 mb30">
			信用等级管理
			@if (check_perm('write_credit_level', FALSE))
			<span class="pull-right add-role">
				<a class='button button-rounded button-flat' href="{{url('admin/setting/credit-level-add')}}">+ 添加信用等级</a></span>
			@endif
		</h4>
		<div class="pannel-body table-responsive">
			<table class="table table-hover table-striped" id="content-table">
				<thead>
					<tr>
						<th class='unsortable'>名称</th>
						<th>等级序列</th>
						<th>最小积分</th>
						<th>最大积分</th>
						<th class='unsortable'>图片</th>
						<th>费用比例</th>
						@if (check_perm('write_credit_level', FALSE))
						<th class='unsortable'>操作</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@foreach ($objs as $obj)
					<tr class="role-{{ $obj->id }}">
						<td>{{ $obj->name }}</td>
						<td>{{ $obj->level }}</td>
						<td>{{ $obj->credit_min }}</td>
						<td>{{ $obj->credit_max }}</td>
						<td><a href="/{{ $obj->image }}" target='_blank'>
							<img src="/{{ $obj->image }}" class="img-rounded img-responsive"/>
						</a></td>
						<td>{{ $obj->margin_rate }} %</td>
						@if (check_perm('write_credit_level', FALSE))
						<td>
							<a class="del-btn" href="{{ url('admin/setting/credit-level-remove', array($obj->id)) }}">删除</a>
						</td>
						@endif
					</tr>
					@endforeach
				</tbody>

			</table>
		</div>
		
	</div>
</div>

	
@stop

@section('inline-js')
{{ HTML::script('assets/js/vendor/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/vendor/bootbox.min.js') }}
{{ HTML::script('assets/js/data_table.js') }}
{{ HTML::script('assets/js/confirm_modal.js') }}
<script>
$(function(){
	init_data_table('content-table', false, false, '');
	init_confirm_modal('del-btn', '确定要删除该记录？');
	
})
</script>
@stop