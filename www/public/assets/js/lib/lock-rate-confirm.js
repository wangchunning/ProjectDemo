$(function(){
    
    $(".bank-select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth : true
    });

    var deposit_currency = $('input[name="deposit_currency"]').val();
    var need2pay_amount = $('input[name="need2pay_amount"]').val();
    var pay_total_amount = $('input[name="total_amount"]').val();

    // 选择支付方式
    $('input[name="payment_method"]').change(function(){
        var select = $('input[name="payment_method"]:checked').val();
        if (select == 'balance')
        {
            $('#balance_amount').removeClass('hidden').addClass('show');
            $('#deposit_amount').removeClass('show').addClass('hidden');
            
            $('#bank-select-div').addClass('hide');
            if (need2pay_amount > 0)
            {
                $('#bank-select-div').removeClass('hide');
                selectDepositBank('deposit-bank-select', need2pay_amount, deposit_currency);
            }

        } else {
            $('#balance_amount').removeClass('show').addClass('hidden');
            $('#deposit_amount').removeClass('hidden').addClass('show');   
            $('#bank-select-div').removeClass('hide');     

            selectDepositBank('deposit-bank-select', pay_total_amount, deposit_currency);    
        }
    });
    $('input[name="payment_method"]').trigger('change');

    $("#deposit-bank-select").on('change', function(){
        $('input[name=deposit_bank]').val($(this).val());
    });

    /**
     * deposit bank select
     */

    /*if (need2pay_amount > 0)
    {
        $('#bank-select-div').removeClass('hide');
        selectDepositBank('deposit-bank-select', need2pay_amount, deposit_currency);
    }
    */
})
