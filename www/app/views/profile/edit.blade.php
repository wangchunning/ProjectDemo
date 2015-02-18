@section('title')
编辑个人信息
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
@include('breadcrumb')

<div class="container flat-panel">
    <div class="all-radius white-box-body">
        
        @if ($errors->has() OR Session::has('error'))
        <div class="alert alert-danger">
            {{ $errors->has() ? $errors->first() : Session::get('error') }}
        </div>
        @endif 

        <div id="handingMsg"></div>

        {{ Form::open(array('url' => url('/profile/edit', $user->uid), 'role' => 'form', 'id' => 'frm', 'class'=>'f14p')) }}
        
        <h4><i class="fa fa-user"></i>  个人信息
            <small class="pull-right">状态：	
            @if ($user->has_all_verified())
			<span class="label label-primary">已完成认证</span>
			@else
			<span class="label label-warning">未完成认证</span>
			@endif
            </small>
        </h4>
        <hr>
    
        <div class="form-group row">
			<label class="col-sm-2 mt10 text-center" for="education">最高学历</label>
			<div class="col-sm-3">
				{{ Form::select('education', UserProfile::education_arr(), $profile->education, array('class' => 'form-control')) }}
			</div>
        </div>
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="education_school">毕业学校</label>
			<div class="col-sm-3">
				<input type="text" name="education_school"
                    class="form-control" placeholder="" value="{{ $profile->education_school }}" >
			</div>
        </div>
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="marriage">婚姻状况</label>
			<div class="col-sm-3">
				{{ Form::select('marriage', UserProfile::marriage_arr(), $profile->marriage, array('class' => 'form-control')) }}
			</div>
        </div>       
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="address">居住地址</label>
			<div class="col-sm-3">
				<input type="text" name="address"
                    class="form-control" placeholder="" value="{{ $profile->address }}"
                    data-bv-notempty="true"
	                data-bv-notempty-message="* 请输入居住地址">
			</div>
        </div>
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="company_category">公司行业</label>
			<div class="col-sm-3">
				{{ Form::select('company_category', UserProfile::company_category_arr(), $profile->company_category, array('class' => 'form-control')) }}
			</div>
        </div>
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="company_scale">公司规模</label>
			<div class="col-sm-3">
				{{ Form::select('company_scale', UserProfile::company_scale_arr(), $profile->company_scale, array('class' => 'form-control')) }}
			</div>
        </div>                                           
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="position">职位</label>
			<div class="col-sm-3">
				<input type="text" name="position"
                    class="form-control" placeholder="" value="{{ $profile->position }}"
                    data-bv-notempty="true"
	                data-bv-notempty-message="* 请输入职位信息">
			</div>
        </div>
        <div class="form-group row mt20">
			<label class="col-sm-2 mt10 text-center" for="salary">薪资范围</label>
			<div class="col-sm-3">
				{{ Form::select('salary', UserProfile::salary_arr(), $profile->salary, array('class' => 'form-control')) }}

			</div>
        </div>  
        <hr class="mt20 mb30">                
        <button type="submit" class="button button-rounded button-flat-primary">更新个人信息</button>                     
        
        {{ Form::close() }}
    </div>

</div>
@stop

@section('inline-css')
 
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
})

</script>    
@stop
