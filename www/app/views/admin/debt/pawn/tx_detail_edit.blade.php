@section('title')
编辑项目信息
@stop

@section('content')
@include('admin.debt.subnav')

<div class="container">
	<div class="dashboard-body">
		
		<div class="flat-panel">
	        
	        @if ($errors->has() OR Session::has('error'))
	        <div class="alert alert-danger">
	            {{ $errors->has() ? $errors->first() : Session::get('error') }}
	        </div>
	        @endif 

			<h4>编辑项目信息</h4>

            {{ Form::open(array('url' => '/admin/debt/pawn-detail-edit/'.$tx->id, 'role' => 'form', 'id' => 'frm')) }}

								
			<div class="form-group row mt30">
				<label for="amount" class="col-sm-2 text-right mt5">公司名称</label>
				<div class="col-sm-4 pr0">
					<input type="text" name="company_name" id="company_name" class="form-control" 
						value="{{ Input::old('company_name', $profile->company_name) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入公司名称"
                		data-bv-stringlength="true"
                		maxlength="50"
                		data-bv-stringlength-message="* 最多 50 个字">
				</div>
			</div>
	        <div class="form-group row">
				<label class="col-sm-2 mt5 text-right" for="industry">公司行业</label>
				<div class="col-sm-3">
					<select name="industry" class="form-control">
						@foreach (UserProfile::company_category_arr() as $key => $value)
						<option value="{{$value}}" {{$value == Input::old('industry', $profile->industry) ? 'selected' : ''}}>{{$value}}</option>
						@endforeach
					</select>
				</div>
			</div>	
			<div class="form-group row">
				<label for="amount" class="col-sm-2 text-right mt5">资金用途</label>
				<div class="col-sm-4 pr0">
					<input type="text" name="purpose" id="purpose" class="form-control" 
						value="{{ Input::old('purpose', $profile->purpose) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入资金用途"
                		data-bv-stringlength="true"
                		maxlength="50"
                		data-bv-stringlength-message="* 最多 50 个字">
				</div>
			</div>		
			<div class="form-group row">
				<label for="amount" class="col-sm-2 text-right mt5">还款来源</label>
				<div class="col-sm-4 pr0">
					<input type="text" name="repayment_from" id="repayment_from" class="form-control" 
						value="{{ Input::old('repayment_from', $profile->repayment_from) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入还款来源"
                		data-bv-stringlength="true"
                		maxlength="50"
                		data-bv-stringlength-message="* 最多 50 个字">
				</div>
			</div>			
			<div class="form-group row">
				<label for="amount" class="col-sm-2 text-right mt5">公司所在省/市</label>
				<div class="col-sm-2 pr0">
					<input type="text" name="state" id="state" class="form-control" 
						value="{{ Input::old('state', $profile->state) }}"
						placeholder="省"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入公司所在省"
                		data-bv-stringlength="true"
                		maxlength="20"
                		data-bv-stringlength-message="* 最多 20 个字">
				</div>
				<div class="col-sm-2 pr0">
					<input type="text" name="city" id="city" class="form-control" 
						value="{{ Input::old('city', $profile->city) }}"
						placeholder="市"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入公司所在城市"
                		data-bv-stringlength="true"
                		maxlength="20"
                		data-bv-stringlength-message="* 最多 20 个字">
				</div>
			</div>	
			<div class="form-group row">
				<label for="amount" class="col-sm-2 text-right mt5">公司注册日期</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="register_date" id="register_date" class="form-control datepicker" 
						value="{{ Input::old('register_date', $profile->register_date) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请选择日期"
                		data-bv-stringlength="true"
                		maxlength="10"
                		data-bv-stringlength-message="* 最多10 个字">
                		<span class="form-control-feedback mr5"><i class="fa fa-calendar grey"></i></span>
				</div>
			</div>	
			
			<div class="form-group row">
				<label for="amount" class="col-sm-2 text-right mt5">公司注册资本</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="register_capital" id="register_capital" class="form-control" 
						value="{{ Input::old('register_capital', $profile->register_capital) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入公司注册资本（万元）"
                		data-bv-regexp="true"
                		pattern="(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})$)"
                		data-bv-regexp-message="* 请输入有效金额">
                		<span class="form-control-feedback mr5">万元</span>
				</div>
			</div>
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right mt5">资产净值</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="property_value" id="property_value" class="form-control" 
						value="{{ Input::old('property_value', $profile->property_value) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入资产净值（万元）"
                		data-bv-regexp="true"
                		pattern="(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})$)"
                		data-bv-regexp-message="* 请输入有效金额">
                		<span class="form-control-feedback mr5">万元</span>
				</div>
			</div>																
			<div class="form-group row mt20">
				<label for="amount" class="col-sm-2 text-right mt5">上年度经营收入</label>
				<div class="col-sm-3 pr0">
					<input type="text" name="last_year_income" id="last_year_income" class="form-control" 
						value="{{ Input::old('last_year_income', $profile->last_year_income) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入上年度经营收入（万元）"
                		data-bv-regexp="true"
                		pattern="(^[1-9]\d*(\.\d{1,2})?$)|(^[0]{1}(\.\d{1,2})$)"
                		data-bv-regexp-message="* 请输入有效金额">
                		<span class="form-control-feedback mr5">万元</span>
				</div>
			</div>	


			<div class="form-group row mt30">
				<label for="amount" class="col-sm-2 text-right mt5">抵(质)押物</label>
				<div class="col-sm-4 pr0">
					<input type="text" name="pawn" id="pawn" class="form-control" 
						value="{{ Input::old('pawn', $profile->pawn) }}"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入抵(质)押物"
                		data-bv-stringlength="true"
                		maxlength="50"
                		data-bv-stringlength-message="* 最多50 个字">
				</div>
			</div>	
			<div class="form-group row mt30">
				<label for="amount" class="col-sm-2 text-right mt5">借款描述</label>
				<div class="col-sm-10 pr0">
					<textarea id="desc" name="desc" class="form-control" placeholder="20 ~ 500个字" rows="5"
						data-bv-notempty="true"
                		data-bv-notempty-message="* 请输入借款描述"
                		data-bv-stringlength="true"
                		minlength="20"
                		maxlength="500"
                		data-bv-stringlength-message="* 20 ~ 500 个字">{{ Input::old('desc', $profile->desc) }}</textarea>
				</div>
			</div>			
			<hr class="mt30">
			<div class="ml20 mt30 row">
				<span class="col-sm-4">
					<button type="submit" class="button button-rounded button-flat-primary">确认提交</button>     
				</span>	                                   
			</div>
			
            {{ Form::close() }}
            
		</div>
	</div>
</div>    
@stop

@section('inline-js')

<script>
$(function(){
    $('#frm').bootstrapValidator({
    	//live: 'disabled'
    });
    
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth : false
    });
    
    $(".datepicker").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language:'zh-CN',
        minView: 2,
        viewSelect: 2
    });

    $('#desc').summernote({
    	height: 200,
    	lang: 'zh-CN',
        onImageUpload: function(files, editor, welEditable) {
            sendFile(files[0], editor, welEditable);
        }
    });

    function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);//咱不支持图片
        $.ajax({
            data: data,
            type: "POST",
            url: '/',
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                editor.insertImage(welEditable, url);
            }
        });
    }
    

});

</script>
@stop
