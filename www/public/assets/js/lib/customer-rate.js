$(function(){

    // 获取汇率且每隔15秒刷新
    getQuotes();
    window.setInterval("getQuotes()", 15000);
})

function getQuotes() {
    if ( ! tickList) return false;

    try {
        $.ajax({
            url: "/quote",
            data: "symbols=" + tickList,
            dataType: "json",
            success: quoteCallSucceed,
            failure: function(result) {
                alert("failure error: " + result);
            },
            error: function(result) {
                alert("call failed: " + result);
            }
        });
    } catch (e) {
        alert('Failed to call web service. Error: ' + e);
    }    
}

/**
 * 获取汇率成功回调函数
 *
 * @param  rep
 * @return void
 */
function quoteCallSucceed(response) {
    if (response.status == 'error') {
        alert(response.data.msg);
        return;
    }
    try {
        var ticks = response.Ticks;
        refreshQuote(ticks);
    } catch (e) {
        alert("quote parse error: " + response);
    }    
}

/**
 * 填充汇率数据到表格
 *
 * @return void
 */
 function refreshQuote(ticks) {
    $.each(ticks ,function(i, tick){
        $('tr.' + tick.Symbol).find('.time').html(tick.TickTime);
        // Rate margin
        $('tr.' + tick.Symbol).find('.bid').html(tick.Bid);
        $('tr.' + tick.Symbol).find('.ask').html(tick.Ask);

    }) 
 }
