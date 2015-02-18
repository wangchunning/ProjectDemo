@section('title')
{{$sub_title}}
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container bank">
        <div class="white-box-body all-radius">
            <h3 class="mt0">{{$sub_title}} <a class="underline back-btn f16p" href="/admin/bank">back</a></h3>
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>                  
            @endif      
            {{ Form::open(array('url' => isset($bank_info) ? 'admin/bank/edit/'.$bank_info->id : 'admin/bank/add', 'role' => 'form', 'id' => 'bankFrm')) }}
                <hr>
                <div class="form-group row">
                    <label for="country" class="col-sm-2 control-label">Choose a Country</label>
                    <div class="col-sm-2">
                        {{ Form::text('country', Input::old('country', isset($bank_info) ? $bank_info->country : 'Australia'), array('class' => 'form-control validate[required] validate[ajax[ajaxCountry]]', 'placeholder' => 'Country')) }}                                                                        
                    </div>
                </div>
                <div class="form-group row">
                    <label for="currency" class="col-sm-2 control-label">Currency</label>
                    <div class="col-sm-10">
                        <ul class="list-inline groupCheck">
                        @foreach ($currencies as $currency)
                            <li><input name="currency[]" class="validate[required]" <?php $old_currency = Input::old('currency') ? implode('|', Input::old('currency')) : (isset($bank_info) ? $bank_info->currency : ''); echo stristr($old_currency, $currency) ? 'checked ' : ''; ?> value="{{$currency}}" type="checkbox"> {{$currency}} <img src="/assets/img/flags/{{$currency}}.png" width ="24" height = "16"></li>
                        @endforeach
                        </ul>
                    </div>
                </div>

                <h3>Account Detail</h3>
                <hr>               
                <div class="form-group row">
                    <div class="col-sm-5">                
                        <label for="account">Account Name</label>
                        <input type="text" name="account" class="form-control validate[required]" id="account" value="{{ Input::old('account', (isset($bank_info) ? $bank_info->account : '')) }}">                        
                    </div>
                </div>                 
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label>BSB</label>
                        <input type="text" name="BSB" class="form-control bsb validate[required]" placeholder="BSB" id="account_bsb" value="{{ Input::old('BSB', (isset($bank_info) ? $bank_info->BSB : '')) }}">
                    </div>
                    <div class="col-sm-3">
                        <label>Account Number</label>
                        <div>
                            <input type="text" name="account_number" class="form-control account_number validate[custom[required_if_not[bank_name, Westpac]]]" placeholder="Account No." id="account_number" value="{{ Input::old('account_number', (isset($bank_info) ? $bank_info->account_number : '')) }}">
                        </div>
                    </div>
                    <div class="col-sm-7 as_customer_num">
                        <label class="hidden-mb"></label>
                        <div class="mt5">
                            <input type="checkbox" disabled id="as_customer_number">
                            <input type="hidden" name="as_customer_num" id="as_customer_num" value="0">
                            <span class="color-999">Replace Customer Number As Account Number</span>
                        </div>
                    </div>                 
                </div>

                <div class="form-group row">
                    <div class="col-sm-3">                
                        <label for="limit">Add Fund Limit</label>
                        <input type="text" name="limit" disabled class="form-control amount-format" id="limit" value="{{ number_format(Input::old('limit', (isset($bank_info) ? $bank_info->limit : 0)), 2) }}">                                           
                    </div>
                    <div class="col-sm-9 add-fund-limit">
                        <label class="hidden-mb"></label>
                        <div class="mt5">
                            <input type="checkbox" {{ Input::old('limit', (isset($bank_info) ? $bank_info->limit : 0)) == 0 ? 'checked' : '' }} name="unlimited" id="unlimited">
                            <span class="color-999">Unlimited</span>
                        </div>
                    </div>
                </div>

                <h3>Bank Detail</h3>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="swift_code">SWIFT Code</label>
                        <input type="text" name="swift_code" class="form-control validate[ajax[ajaxSwiftCode]]" id="swift_code" value="{{ Input::old('swift_code', (isset($bank_info) ? $bank_info->swift_code : '')) }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7">
                        <label for="bank">Bank Name</label>
                        <input type="text" value="{{Input::old('bank', (isset($bank_info) ? $bank_info->bank : ''))}}" class="form-control validate[required]" id="bank_name" name="bank" tabindex="-1">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="branch">Branch</label>
                        <input type="text" name="branch" class="form-control validate[required]" id="bank_branch" value="{{ Input::old('branch', (isset($bank_info) ? $bank_info->branch : '')) }}">
                    </div>
                </div>
                <span class="help-block">Address</span>
                <div class="form-group row">
                    <div class="col-sm-5">
                        <label for="unit_number">Unit Number or Building Name and Level</label>
                        <input type="text" name="unit_number" class="form-control" id="bank_unit_number" value="{{ Input::old('unit_number', (isset($bank_info) ? $bank_info->unit_number : '')) }}">                    
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="street_number">Street Number</label>
                        <input type="text" name="street_number" class="form-control validate[required]" id="bank_street_number" value="{{ Input::old('street_number', (isset($bank_info) ? $bank_info->street_number : '')) }}">
                    </div>
                    <div class="col-sm-4">
                        <label for="street">Street Name</label>
                        <input type="text" name="street" class="form-control validate[required]" id="bank_street" value="{{ Input::old('street', (isset($bank_info) ? $bank_info->street : '')) }}">
                    </div>
                </div>                                      
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="city">Suburb or City</label>
                        <input type="text" name="city" class="form-control validate[required]" id="bank_city" value="{{ Input::old('city', (isset($bank_info) ? $bank_info->city : '')) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control validate[required]" id="bank_state" value="{{ Input::old('state', (isset($bank_info) ? $bank_info->state : '')) }}">
                    </div>
                    <div class="col-sm-2">
                        <label for="postcode">Postcode</label>
                        <input type="text" name="postcode" class="form-control validate[required,custom[number]]" id="bank_postcode" value="{{ Input::old('postcode', (isset($bank_info) ? $bank_info->postcode : '')) }}">
                    </div>                    
                </div>
                
                <h3>Status</h3>                    
                <hr>                               
                <div class="form-group row">
                    <div class="col-sm-3">
                        {{ Form::select('status', array('1' => 'On', '0' => 'Off'), Input::old('status', (isset($bank_info) ? $bank_info->status : '')), array('class' => 'form-control')) }}
                    </div>
                </div> 
                @if (check_perm(isset($bank_info) ? 'edit_bank' : 'add_bank', FALSE))
                <div class="form-group row"> 
                    <div class="col-sm-5">                                              
                        <button type="submit" class="btn btn-primary mt40 mb40">Save</button>
                        Or
                        <a class="underline" href="/admin/bank">Cancel</a>
                    </div>
                </div> 
                @endif               
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-css')
{{ HTML::style('assets/jquery-ui/jquery-ui.min.css') }}
{{ HTML::style('assets/css/typeahead.js-bootstrap.css') }}
@stop

@section('inline-js')
{{ HTML::script('/assets/js/jquery.maskedinput.min.js') }}
{{ HTML::script('assets/js/typeahead.bundle.min.js') }}
{{ HTML::script('assets/js/lib/recipient.js')}}
{{ HTML::script('assets/js/lib/swiftcode.js')}}
<script>
$(function(){
    $('#account_number').numerical();
    $('#BSB').mask("99 9999");
    $("#bankFrm").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });

    $('.amount-format').numerical({decimal:true});
    $('.amount-format').on('change', function(){
        var val = number_format($(this).val(), 2, '.', '');
        $(this).val(val);
    });
    $("#bank_name").autocomplete({
      source: ['Commonwealth Bank', 'National Bank of Australia', 'Westpac', 'ANZ']
    });

    // 检查所填银行与所选择货币
    check_bank_and_currency();
    $('#bank_name').blur(function(){
        check_bank_and_currency();
    });
    $('input[name="currency[]"]').on('click', function(){
        check_bank_and_currency();
    });

    // 启用限额
    check_limit_active();
    $('#unlimited').on('click', function(){
        check_limit_active();
    });
  
})

function check_bank_and_currency(){
    var has_AUD = false;
    $('input[name="currency[]"]:checked').each(function(){
        if ($(this).val() == 'AUD') {
            has_AUD = true;
        }
    });

    // 所选的银行为 Westpac 而且 货币为AUD
    if ($('#bank_name').val() == 'Westpac' && has_AUD) {
        $('#as_customer_number').prop('checked', true).attr('disabled', 'disabled');
        $('#as_customer_num').val('1');
    } else {
        $('#as_customer_number').prop('checked', false);
        $('#as_customer_num').val('0');
    };
}

function check_limit_active(){
    if ($('#unlimited').is(':checked')) {
        $('#limit').attr('disabled', 'disabled');       
    }else{
        $('#limit').removeAttr('disabled');
    };
}
</script>
@stop
