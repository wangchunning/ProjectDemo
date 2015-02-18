@section('title')
Edit - Customers
@stop

@section('content')
    @include('admin.customer.subnav')
    <div class="container">
        @if (check_perm('upgrade_business', FALSE))
            @if ($user->profile->type === 'personal')
        <div class="white-box-body hide flat-panel" id="business-info"> 
            @else
        <div class="white-box-body flat-panel" id="business-info"> 
            @endif
            {{ Form::open(array('url' => '/admin/customers/edit-business/' . $user->uid, 'role' => 'form', 'id' => 'businessFrm')) }}
            <h4>Business Information</h4>
            <span class="help-block">Please enter your ABN or ACN</span>
            <div class="form-group row">
                <div class="col-sm-5">                
                    <label for="abn">ABN/ACN</label>
                    <input type="text" name="abn" class="form-control validate[required]" id="abn" value="{{ Input::old('abn', isset($user->business->abn) ? $user->business->abn : '') }}">
                </div>
            </div>   
            <span class="help-block" id="abn-label"></span>                              
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="business_name">Business Name</label>
                    <input readonly type="text" name="business_name" class="form-control validate[required]" id="business_name" value="{{ Input::old('business_name', isset($user->business) ? $user->business->business_name : '') }}">
                </div>
            </div>
            <h4>Address</h4>
            <span class="help-block">We're required to validate your address. Can't accept a PO box</span>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="unit_number">Unit Number or Building Name and Level</label>
                    <input type="text" name="unit_number" class="form-control" id="unit_number" value="{{ Input::old('unit_number', isset($user->business) ? $user->business->unit_number : '') }}">                    
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="street_number">Street Number</label>
                    <input type="text" name="street_number" class="form-control validate[required]" id="street_number" value="{{ Input::old('street_number', isset($user->business) ? $user->business->street_number : '') }}">
                </div>
                <div class="col-sm-6">
                    <label for="street">Street Name</label>
                    <input type="text" name="street" class="form-control validate[required]" id="street" value="{{ Input::old('street', isset($user->business) ? $user->business->street : '') }}">
                </div>                   
            </div>                                      
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="city">Suburb or City</label>
                    <input type="text" name="city" class="form-control validate[required]" id="city" value="{{ Input::old('city', isset($user->business) ? $user->business->city : '') }}">
                </div>
                <div class="col-sm-2">
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control validate[required]" id="state" value="{{ Input::old('state', (isset($user->business) ? $user->business->state : '')) }}">
                </div>
                <div class="col-sm-2">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="postcode" value="{{ Input::old('postcode', isset($user->business) ? $user->business->postcode : '') }}">
                </div>                    
            </div>  
            <hr> 
            <div class="handingMsg"></div>                              
            <button type="button" id="update-business" class="btn btn-primary">Update</button> Or <a class="hide-business" href="javascript:;">Cancel</a>            
            {{ Form::close() }}                       
        </div>
        @endif    
        <div class="all-radius white-box-body flat-panel">
            <div id="handingMsg"></div>  
            @if ($user->profile->type === 'personal' && 
                    check_perm('upgrade_business', FALSE))          
            <div class="mt20 mb20">
                <a class="underline show-business" href="javascript:;">Upgrade to Business Account</a>
            </div>
            @endif
            {{ Form::open(array('url' => 'admin/customers/edit/' . $user->uid, 'role' => 'form', 'id' => 'personalFrm')) }}
                <h4>
                    Edit {{ sprintf('%s %s %s', $user->first_name, $user->middle_name, $user->last_name) }}
                    <span class="back-btn f14p">{{ link_to('admin/customers', 'Back') }}</span>
                </h4> 

                <div class="form-group row mt20">
                    <div class="col-sm-5">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" autocomplete="off" class="form-control validate[required,custom[email]]" id="email" placeholder="eg. me@wexchange.com" value="{{ Input::old('email', $user->email) }}">
                    </div>
                    <div class="col-sm-3 mt30">
                        <a href="javascript:;" id="resend-email-btn" data-uid="{{ $user->uid }}">Resend Verification Email</a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5">                
                        <label for="password">Password</label>
                        <input type="password" name="password" autocomplete="off" class="form-control" id="password" value="********" disabled>
                    </div>
                    <div class="col-sm-3 mt30">
                        <a href="javascript:;" id="reset-password" data-uid="{{ $user->uid }}">Reset Password</a>
                    </div>
                </div>
                <hr>

                <h4>Name</h4>
                <span class="help-block">Please enter you full legal name</span>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control validate[required]" id="first_name" value="{{ Input::old('first_name', $user->first_name) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="middle_name">Middle (If any)</label>
                        <input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ Input::old('middle_name', $user->middle_name) }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control validate[required]" id="last_name" value="{{ Input::old('last_name', $user->last_name) }}">
                    </div>
                    @if (check_perm('upgrade_vip', FALSE))
                    <div class="col-sm-3">
                        <label for="vip_type">VIP Customers</label>
                        {{ Form::select('vip_type', array('Basic' => 'Basic', 'Silver' => 'Silver', 'Golden' => 'Golden', 'Platinum' => 'Platinum'), Input::old('vip_type', $user->profile->vip_type), array('class' => 'form-control')) }}
                    </div>
                    @endif
                </div>

                <h4>Date of Birth</h4>
                <span class="help-block">Australian law requires us to record your date of birth</span>
                <div class="form-group row">
                    <div class="col-sm-1">
                        <label for="birth_day">dd</label>
                        <input type="text" name="birth_day" class="form-control validate[required]" id="birth_day" value="{{ Input::old('birth_day', $user->profile->birth_day) }}">
                    </div>
                    <div class="col-sm-1">
                        <label for="birth_month">mm</label>
                        <input type="text" name="birth_month" class="form-control validate[required]" id="birth_month" value="{{ Input::old('birth_month', $user->profile->birth_month) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="birth_year">yyyy</label>
                        <input type="text" name="birth_year" class="form-control validate[required]" id="birth_year" value="{{ Input::old('birth_year', $user->profile->birth_year) }}">
                    </div>
                </div>

                <h4>Address</h4>
                <span class="help-block">We're required to validate your address. Can't accept a PO box</span>
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="unit_number">Unit Number or Building Name and Level</label>
                        <input type="text" name="unit_number" class="form-control" id="unit_number" value="{{ Input::old('unit_number', $user->profile->unit_number) }}">                    
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="street_number">Street Number</label>
                        <input type="text" name="street_number" class="form-control validate[required]" id="street_number" value="{{ Input::old('street_number', $user->profile->street_number) }}">
                    </div>
                    <div class="col-sm-6">
                        <label for="street">Street Name</label>
                        <input type="text" name="street" class="form-control validate[required]" id="street" value="{{ Input::old('street', $user->profile->street) }}">
                    </div>                  
                </div>                                      
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="city">Suburb or City</label>
                        <input type="text" name="city" class="form-control validate[required]" id="city" value="{{ Input::old('city', $user->profile->city) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control validate[required]" id="state" value="{{ Input::old('state', $user->profile->state) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="postcode">Postcode</label>
                        <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="postcode" value="{{ Input::old('postcode', $user->profile->postcode) }}">
                    </div>                    
                </div>  
                <hr> 
                <div class="handingMsg"></div>               
                <button type="button" id="save-basic" class="btn btn-primary">Save</button> Or {{ link_to('/admin/customers', 'Cancel') }}                         
            {{ Form::close() }}
        </div>

    </div>
@stop

@section('inline-js')
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('/assets/js/lib/admin/customers.js') }}
{{ HTML::script('/assets/js/lib/reg-business.js') }}
<script>
$(function(){
    $('.show-business').click(function(){
        $('#business-info').removeClass('hide');
        $('.show-business').addClass('hide');
    });
    $('.hide-business').click(function(){
        $('#business-info').addClass('hide');
        $('.show-business').removeClass('hide');
    });

    $('#save-basic,#update-business').click(function(){
        var obj = $(this);
        $.ajax({
            type: "POST",
            url: obj.parents('form').attr('action'),
            data: obj.parents('form').serialize(),
            dataType:'json',
            success: function(rep){
                var className = rep.status === 'ok' ? 'success' : 'danger';
                if(rep.status == 'error'){
                    obj.prev('.handingMsg').html('<div class="alert alert-' + className + '">' + rep.data.msg + '</div>');
                    $.fancybox.hideLoading();
                }else{
                    obj.prev('.handingMsg').html('');
                    $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                    setTimeout('$.fancybox.hideLoading()', 1500);
                }
            },
            error:function (){
                $.fancybox.hideLoading();
            },
            beforeSend : function() {
                $.fancybox.showLoading();    
            },
        });
    });
    
    $("#personalFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
    $("#businessFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });
    
})
</script>
@stop
