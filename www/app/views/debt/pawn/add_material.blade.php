@section('title')
上传认证材料 - 天添财富
@stop

@section('content')
@include('breadcrumb')

<div class="container">
	<div class="dashboard-body">
		
		<div class="flat-panel">
        
	        @if ($errors->has() || Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif      

        	{{ Form::open(array('url' => '/debt/pawn-add-material', 'role' => 'form', 'id' => 'frm')) }}
            
            <h4>上传认证材料</h4>
			<hr>
            <div class="row">
                <div class="col-sm-6">
					<div class="">
						<h5>公司认证</h5>
						<ul class="list-unstyled tx-material-ul">
							<li>请同时上传以下两项资料：</li>
							<ul class="mt10">
								<li>注册满1年的营业执照正、副本。查看示例</li>
								<li>经营场地租赁合同＋90天内的租金发票或水电单据。查看示例</li>
							</ul>
							<li>认证条件：本人名下的企业经营时间需满1年</li>
							<li>认证有效期：6个月</li>
							<li>上传资料：上传资料需清晰完整、未修改。每张图片小于1.5M</li>
						</ul>
					</div>           
				</div>
                <div class="col-sm-6 mt20">
					<div id="company-upload-container">
					    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
					</div>
                </div>
            </div>
            <hr class="mt30">
            <div class="row">
                <div class="col-sm-6">
					<div class="">
						<h5>信用认证</h5>
						<ul class="list-unstyled tx-material-ul">
							<li>个人信用报告需15日内开具。查看示例</li>
							<ul class="mt10 mb10">
								<li><a target="_blank" href="http://www.pbccrc.org.cn/zxzx/lxfs/lxfs.shtml">全国各地征信中心联系方式查询</a></li>
								<li><a href="https://ipcrs.pbccrc.org.cn/" target="_blank">个人信用信息服务平台</a></li>
							</ul>
							<li>认证条件：信用记录良好</li>
							<li>认证有效期：6个月</li>
							<li>如何办理个人信用报告：可去当地人民银行打印，部分地区可登陆个人信用信息服务平台</li>
							<li>上传资料：上传资料需清晰完整、未修改。每张图片小于1.5M</li>
						</ul>
					</div>           
				</div>
                <div class="col-sm-6  mt30">
					<div id="credit-upload-container">
					    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
					</div>
                </div>
            </div>            
            <hr>

            <button type="submit" class="button button-rounded button-flat-primary">下一步</button>  
            	或 <a href="javascript:;" onclick="javascript:history.back(-1);">上一步</a>   
          
        {{ Form::close() }}
    </div>
</div>
@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function(){
	
	multi_file_upload('/upload', '#company-upload-container', 'company_files[]', '#frm');
	multi_file_upload('/upload', '#credit-upload-container', 'credit_files[]', '#frm');

})
</script>
@stop
