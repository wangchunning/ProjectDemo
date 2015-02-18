@section('title')
前台用户管理
@stop

@section('content')

    <div class="container">
        <div class="white-box-body all-radius">
            <div class="flat-panel">
                <h4><i class="fa fa-users"></i> 前台用户管理</h4>
                <hr class="mt20 mb20">
                <div class="filter row mb20">
                	<div class="col-sm-4">
	            		<div class="input-group">
							<input type="text" id="search-input" name="search" class="form-control" placeholder="姓名/Email/手机">
					      	<span class="input-group-btn">
					        	<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					      	</span>
					    </div><!-- /input-group -->
	            	</div> <!--/search-->
                </div>
   
            </div>      
            <div class="pannel-body table-responsive">
                <table class="table table-hover table-striped" id="content-table">
                    <thead>
                        <tr>
                            <th>用户名</th>
                            <th>Email</th>
                            <th>手机</th>
                            <th>信用积分</th>   
                            <th>注册验证</th>                          
                            <th>登录时间</th>
                            <th>注册时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        	@if ($user->status == User::STATUS_DISABLED)
                            <tr class='grey'>
                            @else
                            <tr>
                            @endif
                                <td>
                                    {{ link_to('admin/customers/overview/' . $user->uid, $user->user_name) }}                              
                                    @if ($user->is_vip())                             
                                    <span class="label {{ $user->vip_label_class() }}">
                                        V
                                    </span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->trust_credit }}</td>   
                                <td>{{ $user->has_verified() ? '是' : '否' }}</td>                             
                                <td>{{ $user->login_at }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->status_desc() }}</td>
                              	@if (check_perm('write_customer', FALSE))
								<td>
									@if ($user->status == User::STATUS_NORMAL)
									<a class="status-btn" href="{{ url('admin/customers/user-status', array($user->uid, 'disabled')) }}">禁用</a>
									@elseif ($user->status == User::STATUS_DISABLED)
									<a class="status-btn" href="{{ url('admin/customers/user-status', array($user->uid, 'normal')) }}">启用</a>
									@endif
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

@section('inline-css')

@stop

@section('inline-js')
{{ HTML::script('assets/js/vendor/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/vendor/bootbox.min.js') }}
{{ HTML::script('assets/js/data_table.js') }}
{{ HTML::script('assets/js/confirm_modal.js') }}
<script>
$(function(){
	init_data_table('content-table', false, true, 'search-input');
	init_confirm_modal('status-btn', '确定要修改状态么？');
	
})
</script>
@stop


