$(function(){

    // 初始货币选择框
    $("#balance-selecter, .phone-type").selectBoxIt({
        theme : 'bootstrap'
    });

    // 换汇金额限制只能输入数字
    $('.amount').numerical({decimal:true});

    // 切换currency
    $('#balance-selecter').change(function(){
        var currency = $(this).val();
        window.location.href = '/withdraw/add/' + currency;
    });

    //$('.phone-code').selectize();

    // 检查国家是否自动填写
    selectCountry($('.transfer_country'));

    selectRecipient($('.recipient-select'));

    // 选择收款人，地址簿或新建
    $(document).on('click', '.transfer-to-checkbox', function(){
            
        var value = $(this).val();
        var checked = $(this).prop('checked');

        var parent = $(this).closest('.form-group');
        var new_recipient_panel = parent.siblings('.new-recipient-panel');
        var recipient_bank_account_select = parent.prev().children('.recipient-account-select');

        if (checked)
        {
            var need_unchecked_obj;
            if (value == 'new')
            {
                need_unchecked_obj = parent.prev().find('.transfer-to-checkbox');
            }
            else
            {
                need_unchecked_obj = parent.next().find('.transfer-to-checkbox');
            }
            
            need_unchecked_obj.prop('checked', false);
        }
        else
        {
            $(this).prop('checked', true);
        }
        // js表单验证 hack
        //$('#transfer_to').val(value);

        // 是否显示添加新收款人窗口
        if (value == 'new') {

            new_recipient_panel.removeClass('hide');
            recipient_bank_account_select.attr('disabled', 'disabled').removeClass('has-error');
            recipient_bank_account_select.siblings('.formError').remove();

            var new_select = $("<select class='form-control recipient-select' placeholder='选择收款人信息' name='recipient_id[]'></select>");
            var select_parent = new_recipient_panel.find('.recipient-select-panel').eq(0);
            select_parent.children().remove();
            select_parent.append(new_select);
            selectRecipient(new_recipient_panel.find('.recipient-select').eq(0));

            var new_country = $("<input type='text' name='country[]' placeholder='国家' class='form-control transfer_country validate[required] validate[ajax[ajaxCountry]]' />");
            var country_parent = new_recipient_panel.find('.country-panel').eq(0);
            country_parent.children().remove();
            country_parent.append(new_country);
            selectCountry(new_country);

        } else {
            new_recipient_panel.addClass('hide');
            recipient_bank_account_select.removeAttr('disabled');
        } 
    });

    var currency = $('#balance-selecter').val();
    selectRecipientBankAccount($(".recipient-account-select"), currency);
    //selectRecipient($('.recipient-select'));

    $(document).on('click', '.remove-recipient-account-btn', function(){
        $(this).closest('.clone-panel').remove();
    });


    $('#recipient-pannel').delegate('.add-recipient-account-btn','click',function(){
        
        var parent = $('#recipient-pannel');
        var new_obj = $(this).closest('.clone-panel').clone(true);
        //new_obj.removeClass('hide');
        new_obj.appendTo(parent);

        $(this).addClass('hide');
        $(this).next('.remove-recipient-account-btn').removeClass('hide');
        //$(this).closest('#bank-list-panel-template').prop('id', '');

        var select_parent = new_obj.find('.account-select-panel').eq(0);
        select_parent.children().remove();
        var new_select = $("<select class='form-control recipient-account-select' placeholder='选择收款人银行账号' name='account_id[]'></select>");
        select_parent.append(new_select);
        selectRecipientBankAccount(new_select, currency);

        var new_select = $("<select class='form-control recipient-select' placeholder='选择收款人信息' name='recipient_id[]'></select>");
        var select_parent = new_obj.find('.recipient-select-panel').eq(0);
        select_parent.children().remove();
        select_parent.append(new_select);
        selectRecipient(select_parent.find('.recipient-select').eq(0));

        var new_country = $("<input type='text' name='country[]' placeholder='国家' class='form-control transfer_country validate[required] validate[ajax[ajaxCountry]]' />");
        var country_parent = new_obj.find('.country-panel').eq(0);
        country_parent.children().remove();
        country_parent.append(new_country);
        selectCountry(new_country);

        var new_phonecode_select = $("<select name='phone_code[]' class='form-control phone-code'></select>");
        var new_phonecode_select_parent = new_obj.find('.phone-code-panel').eq(0);
        new_phonecode_select_parent.children().remove();
        new_phonecode_select_parent.append(new_phonecode_select);
        selectPhoneCode(new_phonecode_select_parent.find('.phone-code').eq(0));

    }); 

    // 初始联系人
    /*
    var $recipient = $('.recipient-select').selectize({
        onChange : function(value) {
            if (value != '') {
                $('#choose_from').val(value);
                $('#new-recipient-detail').hide();

                // 兼容表单验证
                $('.recipient-select-panel .selectize-control').removeClass('validate[custom[required_if_not[choose_from,new]]]');
                $('.recipient-select-panel .formError').remove();
            }else{
                $('#choose_from').val('new');
                $('#new-recipient-detail').show();
            };     
        }
    });
    */
    // 点击展开recipient detail
    $('.add-new-recipient-btn').on('click', function(){
        var new_recipient_panel = $(this).closest('.new-recipient-panel');
        new_recipient_panel.find('.new-recipient-detail').eq(0).removeClass('hide');

        var recipient_select = new_recipient_panel.find('.recipient-select').eq(0);

        var new_select = $("<select class='form-control recipient-select' placeholder='选择收款人信息' name='recipient_id[]'></select>");
        var select_parent = new_recipient_panel.find('.recipient-select-panel').eq(0);
        select_parent.children().remove();
        select_parent.append(new_select);
        selectRecipient(select_parent.find('.recipient-select').eq(0));

        //recipient_select_selectize.clear();

        var new_phonecode_select = $("<select name='phone_code[]' class='form-control phone-code'></select>");
        var new_phonecode_select_parent = new_recipient_panel.find('.phone-code-panel').eq(0);
        new_phonecode_select_parent.children().remove();
        new_phonecode_select_parent.append(new_phonecode_select);
        selectPhoneCode(new_phonecode_select_parent.find('.phone-code').eq(0));

    });


    $('.swift_code').blur(function(){
        var swift_code = $(this).val();
        if (swift_code == '')
            return;

        var parent = $(this).closest('.form-group').siblings('.form-group');
        var bank_name = parent.find('.bank_name').eq(0);
        var bank_branch = parent.find('.bank_branch').eq(0);
        var bank_unit_number = parent.find('.bank_unit_number').eq(0);
        var bank_street_number = parent.find('.bank_street_number').eq(0);
        var bank_street = parent.find('.bank_street').eq(0);
        var bank_city = parent.find('.bank_city').eq(0);
        var bank_state = parent.find('.bank_state').eq(0);
        var bank_postcode = parent.find('.bank_postcode').eq(0);

        $.fancybox.showLoading();
        $.ajax({
            url : '/valid/swiftcode-bank',
            dataType : 'json',
            data : {swiftcode : swift_code},
            type : 'GET',
            success : function(rep) {
                $.fancybox.hideLoading();

                bank_name.attr('value',rep.bank);
                bank_branch.attr('value',rep.branch);
                bank_unit_number.attr('value',rep.unit_number);
                bank_street_number.attr('value',rep.street_number);
                bank_street.attr('value',rep.street);
                bank_city.attr('value',rep.city);
                bank_state.attr('value', rep.state);
                bank_postcode.attr('value',rep.postcode);
                            
            }
        })

    }); 

})

function selectRecipientBankAccount(select_obj, currency)
{

    var $select = select_obj.selectize({valueField : 'value'});
    // fetch the instance
    var obj = $select[0].selectize;

    obj.clearOptions();

    $.ajax({
        url : '/recipient/recipient-bank-account/' + currency,
        success : function(rep) {
            $.fancybox.hideLoading();
            if (rep.status == 'ok') {
                obj.addOption(rep.data.data);
                //obj.data("selectBox-selectBoxIt").add(rep.data.data);
            };
        },
        beforeSend : function() {
            $.fancybox.showLoading();
        }
    });    
}

function selectRecipient(select_obj)
{

    var $select = select_obj.selectize({
       // valueField : 'value',

        onChange : function(value) {
            
            var choose_from_obj = select_obj.closest('.recipient-select-panel')
                                                .siblings('.choose_from');
            if (value != '') {
                
                choose_from_obj.attr('value', value);
                var new_recipient_panel = select_obj.closest('.new-recipient-panel');
                new_recipient_panel.find('.new-recipient-detail').eq(0).addClass('hide');

                // 兼容表单验证
                //$('.recipient-select-panel .selectize-control').removeClass('validate[custom[required_if_not[choose_from,new]]]');
                //$('.recipient-select-panel .formError').remove();
            }else{
                choose_from_obj.attr('value', 'new');
                //$('#choose_from').val('new');
                //$('#new-recipient-detail').show();
            };     
        }

    });
    // fetch the instance
    var obj = $select[0].selectize;

    obj.clearOptions();

    $.ajax({
        url : '/recipient/recipient/',
        success : function(rep) {
            $.fancybox.hideLoading();
            if (rep.status == 'ok') {
                obj.addOption(rep.data.data);
            };
        },
        beforeSend : function() {
            $.fancybox.showLoading();
        }
    });    
}

/**
 * 根据国家调整银行表单字段名称
 *
 * @param  string
 * @return void
 */
function adjustBankField(country_obj) {
    
    var country = country_obj.val();
    var panel = country_obj.closest('.form-group').siblings('.account-number-panel');
    var label_obj = panel.find('label[for="account_number"]').eq(0);
    var bsb_obj = panel.find('input[name="account_bsb[]"]').eq(0);
    var account_number_obj = panel.find('input[name="account_number[]"]').eq(0);

    switch (country) {
        case 'Germany': 
            label_obj.html('IBAN');
            bsb_obj.val('').parent().hide();
            account_number_obj.attr('placeholder', 'IBAN');
        break;

        case 'Australia': 
            label_obj.html('银行账号');
            bsb_obj.attr('placeholder', 'BSB').parent().show();
            account_number_obj.attr('placeholder', '银行账号');        
        break;

        case 'United Kingdom':
            label_obj.html('银行账号');
            bsb_obj.attr('placeholder', 'Sort Code').parent().show();
            account_number_obj.attr('placeholder', '银行账号');        
        break;     

        default:
            label_obj.html('银行账号');
            bsb_obj.val('').parent().hide();
            account_number_obj.attr('placeholder', '银行账号');                    
        break;   
    }

    // 验证货币数量
    /*
    if (country == 'China' || country == 'Hong Kong') {
        $('input[name="currency"]').removeClass('validate[maxCheckbox[1]]');
    } else {
        $('input[name="currency"]').addClass('validate[maxCheckbox[1]]');        
    }    
    */
}

function selectCountry(select_obj) 
{
    // 国家联想填充
    var countries = new Bloodhound({
        datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
        prefetch: {
            url: '/query/countries',
            filter: function(list) {
                return $.map(list, function(country) { return { name: country }; });
            }
        }
    });
    countries.initialize();
    select_obj.typeahead({
        minLength : 1,
        hint : false,
        highlight : true,
    }, {
        name : 'countries',
        displayKey : 'name',
        source : countries.ttAdapter()
    })
    .bind('typeahead:closed', function(){
        /**
         * 完成国家填写触发的事件
         */
        var country = select_obj.val();
        // 首字母转为大写
        select_obj.val(upperFirstLetter(country));

        // 调整银行表单字段名称
        adjustBankField(select_obj);

    });

}


function selectPhoneCode(select_obj)
{

    var currency = $('#balance-selecter').val();
    var $select = select_obj.selectize({valueField : 'value'});
    // fetch the instance
    var obj = $select[0].selectize;

    obj.clearOptions();

    $.ajax({
        url : '/recipient/phone-code/' + currency,
        success : function(rep) {
            $.fancybox.hideLoading();
            if (rep.status == 'ok') {
                obj.addOption(rep.data.data);
                //obj.data("selectBox-selectBoxIt").add(rep.data.data);
            };
        },
        beforeSend : function() {
            $.fancybox.showLoading();
        }
    }); 

}

