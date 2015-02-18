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

        {{ Form::open(array('url' => url('/profile/edit-bank-account'), 'role' => 'form', 'id' => 'frm')) }}
        
        <h4><i class="fa fa-money"></i>  提现银行卡信息</h4>

    
        <div class="form-group row mt40">
			<label for="amount" class="col-sm-2 text-center ">持卡人姓名</label>
			<div class="col-sm-3">
				{{ pretty_real_name($profile->real_name) }}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 mt5 text-center" for="bank_name">银行名称</label>
			<div class="col-sm-3">
				{{ Form::select('bank_name', UserBankAccount::bank_arr(), 
					Input::old('bank_name') ? Input::old('bank_name') : (empty($bank_account) ? '' : $bank_account->bank_name), array('class' => 'form-control')) }}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 mt5 text-center" for="bank_full_name">分行名称</label>
			<div class="col-sm-3">
				<input type="text" name="bank_full_name" class="form-control" value="{{ Input::old('bank_full_name') ? Input::old('bank_full_name') : (empty($bank_account) ? '' : $bank_account->bank_full_name) }}"
					data-bv-notempty="true"
					data-bv-notempty-message="* 请输入分行名称">
			</div>
		</div>						
		<div class="form-group row mt20">
			<label for="amount" class="col-sm-2 text-center mt5">银行卡号</label>
			<div class="col-sm-3">
				<input type="text" name="account_number" class="form-control" value="{{ Input::old('account_number') ? Input::old('account_number') : (empty($bank_account) ? '' : $bank_account->account_number) }}"
					data-bv-notempty="true"
					data-bv-notempty-message="* 请输入银行卡号"
					data-bv-regexp="true"
					pattern="(^\d{12,19}$)"
					data-bv-regexp-message="* 请输入有效银行卡号">
			</div>
		</div>	
        <hr class="mt30 mb30">                
        <button type="submit" class="button button-rounded button-flat-primary ml10">更新银行卡信息</button>                     
        
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
