@section('title')
上传认证材料
@stop

@section('content')
@include('admin.debt.subnav')

<div class="container">
	<div class="dashboard-body">
		
		<div class="flat-panel">
        
	        @if ($errors->has() || Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif      

        	{{ Form::open(array('url' => '/admin/debt/pawn-material-add/'.$tx_id, 'role' => 'form', 'id' => 'frm')) }}
            
            <h4>上传认证材料</h4>
			<hr>
            <div class="row">
                <div class="col-sm-6">
              		<div class="form-group row">
              			<div class="col-sm-8">
	                		<label for="m_type">材料类别</label>
		                    <input type="text" class="form-control mt10" name="m_type" placeholder="会显示在前台"
		                        		value="{{ Input::old('m_type', '') }}" 
		                        		data-bv-notempty="true"
		                				data-bv-notempty-message="* 请输入材料类别"
		                				data-bv-stringlength="true"
				                		maxlength="6"
				                		data-bv-stringlength-message="* 最多 6 个字"
		                        		>              			
              			</div>
              		</div>         
				</div>
                <div class="col-sm-6">
					<div id="upload-container">
					    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
					</div>
                </div>
            </div>

            <hr>

            <button type="submit" class="button button-rounded button-flat-primary">确认提交</button>    
          
        	{{ Form::close() }}
    </div>
</div>
@stop

@section('inline-css')

@stop

@section('inline-js')

<script>
$(function(){
	
	multi_file_upload('/upload', '#upload-container', 'm_files[]', '#frm');

})
</script>
@stop
