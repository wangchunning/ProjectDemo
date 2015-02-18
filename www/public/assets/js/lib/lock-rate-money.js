$(function(){

    $(document).ajaxStart(function() {
        $.fancybox.showLoading();
    });
    $(document).ajaxComplete(function(event, xhr, settings) {
        $.fancybox.hideLoading();
    });

    // 开始汇率更新倒计时
    $('#countdown_dashboard').countDown({
        targetOffset :  {
            'day':      0,
            'month':    0,
            'year':     0,
            'hour':     0,
            'min':      mins,
            'sec':      secs,
        },
        onComplete : function() {
            $('#refresh-modal').modal({
                backdrop : 'static'                
            });
        }
    });

    // 点击更新汇率按钮
    $('#refresh-rate-btn').click(function(){
        $.ajax({
            url : '/lockrate/refresh',
            success : function(rep) {
                // 登录超时?
                if (rep.status == 'timeout') window.location.reload();
                // Fx 模块中锁定汇率?
                if (window.location.href.indexOf('fx/confirm') != -1) window.location.reload();

                // 更新汇率相关信息
                $('.lock-rate-label').html(rep.data.rate);
                $('#amount-want-label').html(rep.data.amount);
                $('#lock-time-label').html(rep.data.time);
                // 重置倒计时
                resetCountdown(rep.data.min, rep.data.sec);

                // 登录前后汇率不对应时刷新
                if ($('#vip-rate-desc').length != 0) {
                    $('#vip-rate-desc .rate-label').html(rep.data.rate);
                    $('#vip-rate-desc').show();
                };

                $('#refresh-modal').modal('hide'); 
            }
        })
    });

    // 未登录点击更新汇率按钮
    $('#refresh-rate').click(function(){
        $.ajax({
            url : '/refresh',
            success : function(rep) {
                // 更新汇率相关信息
                $('.lock-rate-label').html(rep.data.rate);
                $('#amount-want-label').html(rep.data.amount);
                $('#lock-time-label').html(rep.data.time);
                // 重置倒计时
                resetCountdown(rep.data.min, rep.data.sec);

                $('#refresh-modal').modal('hide'); 
            }
        })
    });

})

/**
 * 重置倒计时
 *
 * @param  int  倒计的分钟数
 * @param  int  倒计的秒数
 * @return void
 */
function resetCountdown(min, sec)
{
    $('#countdown_dashboard').stopCountDown();
    
    $('#countdown_dashboard').setCountDown({
        targetOffset :  {
            'day':      0,
            'month':    0,
            'year':     0,
            'hour':     0,
            'min':      min,
            'sec':      sec,
        }
    });  

    $('#countdown_dashboard').startCountDown();
}