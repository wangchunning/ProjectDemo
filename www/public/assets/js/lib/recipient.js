$(function(){

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
    $('input[name="country"]').typeahead({
        minLength : 1,
        hint : false,
        highlight : true,
    }, {
        name : 'countries',
        displayKey : 'name',
        source : countries.ttAdapter()
    })
    .on('typeahead:closed', onCompleteCountry);

    // 检查国家是否自动填写
    onCompleteCountry();

    $('input[name="currency"]').on('click', function(){
        // 单选货币
        if ($(this).hasClass('validate[maxCheckbox[1]]')) {
            if($(this).is(':checked')) {
                var checkboxes = $('input[name="currency[]"]');
                checkboxes.prop('checked', false);
                $(this).prop('checked', true);
            }
            return;
        };
    });
})

/**
 * 根据国家调整银行表单字段名称
 *
 * @param  string
 * @return void
 */
function adjustBankField(country) {
    switch (country) {
        case 'Germany': 
            $('label[for="account_number"]').html('IBAN');
            $('#account_bsb').parent().hide();
            $('#account_number').attr('placeholder', 'IBAN');
        break;

        case 'Australia': 
            $('label[for="account_number"]').html('银行账号');
            $('#account_bsb').attr('placeholder', 'BSB').parent().show();
            $('#account_number').attr('placeholder', '银行账号');        
        break;

        case 'United Kingdom':
            $('label[for="account_number"]').html('银行账号');
            $('#account_bsb').attr('placeholder', 'Sort Code').parent().show();
            $('#account_number').attr('placeholder', '银行账号');        
        break;     

        default:
            $('label[for="account_number"]').html('银行账号');
            $('#account_bsb').parent().hide();
            $('#account_number').attr('placeholder', '银行账号');                    
        break;   
    }

    // 验证货币数量
    if (country == 'China' || country == 'Hong Kong') {
        $('input[name="currency"]').removeClass('validate[maxCheckbox[1]]');
    } else {
        $('input[name="currency"]').addClass('validate[maxCheckbox[1]]');        
    }    
}

/**
 * 完成国家填写触发的事件
 *  
 * @return void
 */
function onCompleteCountry() {
    var $country = $('input[name="country"]');
    
    // 首字母转为大写
    $country.val(upperFirstLetter($country.val()));

    // 调整银行表单字段名称
    adjustBankField($country.val());
}
