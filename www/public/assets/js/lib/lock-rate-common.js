$(function(){
    // 是否展开收款人添加窗口
    slideRecipient();  

    // 选择收款人，地址簿或新建
    $('input[type=radio]').change(function(){
        slideRecipient();
    });

    // 选择汇款国家
    $('select[name="country"]').change(function() {
        var country = $(this).val();

        // 更新银行填写表单
        bankDetail(country);

        $('select[name="country"]').val(country);
        $('select[name="phone_code"]').find('option[data-country="' + country + '"]').prop('selected', true);
    });
    $('select[name="country"]').change(function(){
        var country = $(this).val();
        $('select[name="phone_code"]').find('option[data-country="' + country + '"]').prop('selected', true);
    });
    $('select[name="country"],select[name="country"]').trigger('change');          
})

/**
 * 显示或隐藏收款人添加窗口
 *
 * @return void
 */
function slideRecipient()
{
    var select = $('input[type=radio]:checked').val();

    // js表单验证 hack
    $('#transfer_to').val(select);

    // 是否显示 Additional 窗口
    if (select != 'balance')
    {
        $('#additional-pannel').show();
    } else {
        $('#additional-pannel').hide();    
    }

    // 是否显示添加新收款人窗口
    if (select == 'new') {
        $('#new-recipient-pannel').show();
        $('#account_id').attr('disabled', 'disabled').removeClass('has-error');
        $('#account_id').siblings('.formError').remove();
    } else {
        $('#new-recipient-pannel').hide();
        $('#account_id').removeAttr('disabled');
    }    
}
