@section('title')
添加信用等级
@stop

@section('content')


<div class="container deposit-voucher">
    <div class="white-box-body all-radius">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif 

        <h4><i class="fa fa-th-list"></i> 添加信用等级</h4>
        <hr class="mt20">
        <div class="flat-panel">

            {{ Form::open(array('url' => '/admin/setting/credit-level-add', 'role' => 'form', 'id' => 'frm')) }}
                    
               	<div class="form-group row mt10">
                    <label for="level_name" class="col-sm-1 pr0 mt10 control-label">等级名称</label>
                    <div class="col-sm-3">                                
                        <input type="text" name="level_name" class="form-control" placeholder="请输入等级名称"
                        	data-bv-notempty="true"
                			data-bv-notempty-message="请输入等级名称">
                    </div>
                </div> 
               	<div class="form-group row mt10">
                    <label for="level" class="col-sm-1 pr0 mt10 control-label">等级序列</label>
                    <div class="col-sm-3">                                
                        <select name="level" class="form-control" placeholder="请输入等级序列"
                        	data-bv-notempty="true"
                			data-bv-notempty-message="请输入等级序列"
                			data-bv-integer="true"
                			data-bv-integer-message='请输入有效整数'>
                			
                			@for ($idx = 0; $idx < 11; $idx++)
                			<option value="{{$idx}}">{{$idx}}</option>
                			@endfor
                		</select>
                    </div>
                    <div class="col-sm-3 pl0">  
                    	<span class="help-block mt10">1 ~ 10</span>  
                    </div>
                </div> 
                <div class="form-group row">
                    <label for="credit_min" class="col-sm-1 pr0 mt10 control-label">最小积分</label>                                                                       
                    <div class="col-sm-3">
                        <input type="text" name="credit_min" id="credit_min" class="form-control" placeholder="积分范围下限" 
                        	data-bv-notempty="true"
                			data-bv-notempty-message="请输入最小积分"
                			data-bv-integer="true"
                			data-bv-integer-message='请输入有效整数'>   
                    </div>
                </div>               
                <div class="form-group row mt10">             
                    <label for="credit_max" class="col-sm-1 pr0 mt10 control-label">最大积分</label>
                    <div class="col-sm-3">                                   
                        <input type="text" name="credit_max" class="form-control" id="credit_max" placeholder="积分范围上限" 
                        	data-bv-notempty="true"
                			data-bv-notempty-message="请输入最大积分"
                			data-bv-integer="true"
                			data-bv-integer-message='请输入有效整数'>
                    </div>
                </div> 
                <div class="form-group row mt10">             
                    <label for="margin_rate" class="col-sm-1 pr0 mt10 control-label">费用比例(%)</label>
                    <div class="col-sm-3">                                   
                        <input type="text" name="margin_rate" class="form-control" id="margin_rate" placeholder="费用比例" 
                        	data-bv-notempty="true"
                			data-bv-notempty-message="请输入费用比例"
                			data-bv-numeric="true"
                			data-bv-numeric-message="请输入有效数值，如6.25">
                    </div>
                </div> 
		        <div class="form-group row">
		            <label class="col-sm-1 mt5"  for="">图片</label>
		            <div class="col-sm-10" id="img-btn-upload-container">    
		                <button id="img-upload-btn" type="button" class="btn btn-default">选择文件</button>
		                <span id="img-upload-result-container"></span>  
		            </div>          
		            
		        </div>
                <button type="submit" class="button button-rounded button-flat-primary mt30 mb10">提交信用等级</button> 
                {{ Form::close() }}
        </div>


    </div>
</div> <!-- /content -->
@stop

@section('inline-css')

@stop

@section('inline-js')
 
{{ HTML::script('assets/plupload/plupload.full.min.js') }}

<script>
$(function(){

    $('#frm').bootstrapValidator({
    	live: 'true'
    });

    common_file_upload('/admin/upload','img-upload-btn', 'img-btn-upload-container', 'img_file', 'img-upload-result-container');
})
</script>
@stop
