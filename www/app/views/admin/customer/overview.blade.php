@section('title')
用户详情
@stop

@section('content')

<div class="container">
	<div class="white-box-body all-radius">
		<h4>
			{{ $user->user_name }}
			@if ($user->is_vip())                             
			<span class="label ml10 mr10 {{ $user->vip_label_class() }}">
				V
			</span>
			@endif
		</h4>

		<hr>
		<div id="overview-profile-panel">
                <h4>个人信息
                    @if (check_perm('write_customer', FALSE))
                    <span class="ml10 f14p">
                        {{ link_to('admin/customers/edit/'  . $user->uid, 'Edit', array('class' => 'underline')) }}
                    </span>
                    @endif
                </h4>

                <div class="row">
                    <div class="col-xs-6">
                        <p><strong>姓名</strong>{{ $user->user_name }}</p>
                        <p><strong>Email</strong>{{ $user->email }}</p>
                        <p><strong>出生日期</strong>{{-- $profile->birthday --}}</p>
                        <p><strong>地址</strong>{{-- $profile->address --}}</p>
                        <p><strong>手机</strong>{{ $user->mobile}}</p>
                    </div>
                    <div class="col-xs-6">
                        <p><strong>公司</strong>{{-- $profile->company_name --}}</p>
                    </div>                    
                </div>
		</div>
		<hr>
            <!-- Balances -->
            <div id="overview-account-balance">
                <h4>账户余额</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>账户总额</th>                               
                                <th>冻结总额</th>
                                <th>可用余额</th>
                            </tr>
                        </thead>    
                        <tbody>
                            <tr><td colspan="3" align="center">- 没有记录 -</td></tr>
                       
                        </tbody>               
                    </table>
                </div>
            </div>
            <!-- //Balances -->
            <hr>
            <!-- Receipts -->
            <div>
                <h4>借款统计<span class="pull-right"><a class="underline f13p" href="{{ url('admin/customers/receipts', $user->uid) }}">More</a></span></h4>

            </div>
            <!-- //Receipts -->

	</div>
</div>
@stop

@section('inline-js')

<script>

</script>
@stop
