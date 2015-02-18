var countDownId;

$(function(){
    if (window.Chart)
    {
        var chart = new Chart();
    }
    // 换汇金额限制只能输入数字
    $('#amount_have,#amount_want').numerical();

    // 初始货币选择框
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth : false
    });

    // 切换 Local 货币
    $('#currency_have').change(function() {
        var self = $(this);

        // 清空现有列表
        $("#currency_want").data("selectBox-selectBoxIt").remove();

        $.ajax({
            url : '/quote/foreign-currencies',
            data : {currency : self.val()},
            success : function(resp) {
                $("#currency_want").data("selectBox-selectBoxIt").add(resp);  
                // 刷新汇率
                currencyConvert();
                // 刷新图表
               if (window.Chart) chart.getData(getSymbol());
            }
        })
    });
    $('#currency_have').trigger('change'); 

    // 切换 Foreign 货币
    $('#currency_want').change(function(){
        // 刷新汇率
        currencyConvert();
        // 刷新图表
       if (window.Chart) chart.getData(getSymbol());

    });

    // 换汇金额计算
    $('#amount_have').keyup(function(){
        refreshAmount();
    });
    $('#amount_want').keyup(function(){
        var amount = parseFloat($(this).val()) / parseFloat($('#spot-rate').val());
        $('#amount_have').val(isNaN(amount) ? '' : amount.toFixed(2));
    })
})

/**
 * 获取指定两种货币的汇率
 *
 * @return void
 */
function currencyConvert() {
    $('#count-down').html('30s');

    $.ajax({
        url: "/quote/convert",
        data: {symbol : $('#currency_have').val() + $('#currency_want').val()},
        dataType: "json",
        success: convertCallSucceed,
        error: function(result) {
        },
        beforeSend : function() {
            $.fancybox.showLoading();
        }
    })
}

/**
 * 获取汇率成功后的回调函数
 *
 * @param  object
 * @return void
 */
function convertCallSucceed(rep) {
    $.fancybox.hideLoading();

    clearInterval(countDownId);

    if ( ! rep) {
        return false;
    }
    
    if ( ! rep.length) {
        $('.rate-pannel').hide();
        $('#rate-label').html(0);  
        $('#spot-rate').val(0);
        return false;
    }

    // 显示当前汇率
    $('#rate-label').html(rep[0].RateForShow);  // 用于显示
    $('#spot-rate').val(rep[0].Bid);            // 用于计算
    $('#converter-label').html($('#currency_have').val() + '/' + $('#currency_want').val())
    $('.rate-pannel').show();

    // 换算当前金额
    refreshAmount();

    // 更新提示                
    updateTip();              

    // 倒计时开始
    countDown();
}

/**
 * 倒计时刷新汇率
 *
 * @return void
 */
function countDown()
{
    var sec = 29;
    countDownId = setInterval(function(){
        if (sec == 0) {
            currencyConvert();            
        }
        if (sec <= 0) $('#count-down').html('30s');
        else $('#count-down').html(sec + 's');
        sec--;        
    }, 1000);
}

/**
 * 刷新当前换汇金额
 *
 * @return void
 */
function refreshAmount()
{
    var amount = parseFloat($('#amount_have').val()) * parseFloat($('#spot-rate').val());

    $('#amount_want').val(isNaN(amount) ? '' : amount.toFixed(2));
}