$(function(){

    // 汇率条目拖动排序 
    $('.rate-table').sortable({
        opacity : 0.7,
        items : 'tbody tr',
        update : function() {
            var items = [];
            $(this).find('tbody').children("tr").each(function(){
                items.push($(this).attr('data-id'));
            });
            $.ajax({
                url : '/admin/rate/order',
                type : 'POST',
                data : {items : items}
            })            
        }
    }).disableSelection();

    // 打开添加货币窗口
    $('#add-rate-btn').click(function(){
        $(this).hide();
        $('#add-rate-pannel').show();
    });

    // 关闭添加货币窗口
    $('#cancel-btn').click(function(){
        $('#add-rate-pannel').hide();        
        $('#add-rate-btn').show();
    });

    // 提交货币汇率
    $('#save-rate-btn').click(function(){
        $.ajax({
            url : '/admin/rate/add',
            type : 'POST',
            dataType : 'json',
            data : {
                from : $('#currency_from').val(),
                to : $('#currency_to').val()
            },
            success : function(rep) {
                window.location.reload();
            }
        })
    });

    // 移除某栏汇率
    $('.remove-btn').click(function() {
        var obj = $(this);

        bootbox.confirm("Are you sure you want to delete?", function(result) {
            if (result) {
                $.ajax({
                    url : '/admin/rate/remove',
                    type : 'POST',
                    dataType : 'json',
                    data : {
                        currency : obj.closest('tr').attr('class')
                    },
                    success : function(rep) {
                        obj.closest('tr').remove();
                    }
                })
            }
        }); 
    });

    // 修改汇率
    $('#edit-margin-btn').click(function(){
        $(this).hide();
        $('#action-margin-panel').show();
        $('#rate-stop').val(1);
        $('.rate-edit-action').show();
        $('.rate-add-action').hide();

        $.each($('#basic,#silver,#golden,#platinum'), function(i, item){
            var tx_fee_obj = $(this).find('.tx-fee');
            tx_fee_obj.html('<input type="text" class="ml5 mr5" value="'+ tx_fee_obj.text() +'">');    
        });
        

        $.each($('.rate-table tbody tr'), function(i, item){
            var vip_type = $(this).closest('div').attr('id');
            var currency_type = $(item).attr('class');
            var vip_bid_margin = 0;
            var vip_ask_margin = 0;

            switch (vip_type)
            {
            case 'basic' :
                vip_bid_margin = margins[currency_type].bidMargin * 10000;
                vip_ask_margin = margins[currency_type].askMargin * 10000;
                break;
            case 'silver':
                vip_bid_margin = margins[currency_type].silverBidMargin * 10000;
                vip_ask_margin = margins[currency_type].silverAskMargin * 10000;
                break;
            case 'golden':
                vip_bid_margin = margins[currency_type].goldenBidMargin * 10000;
                vip_ask_margin = margins[currency_type].goldenAskMargin * 10000;            
                break;
            case 'platinum':
                vip_bid_margin = margins[currency_type].platinumBidMargin * 10000;
                vip_ask_margin = margins[currency_type].platinumAskMargin * 10000;
                break;
            default:
                vip_bid_margin = margins[currency_type].bidMargin * 10000;
                vip_ask_margin = margins[currency_type].askMargin * 10000;
            }
            
            // 检查是否可编辑
            if ($(item).children('.vip-bid').hasClass('no-edit') === false) {
                $(item).children('.vip-bid').html('<input type="text" value="'+ vip_bid_margin.toFixed(0) +'">');
            };
            if ($(item).children('.vip-ask').hasClass('no-edit') === false) {
                $(item).children('.vip-ask').html('<input type="text" value="'+ vip_ask_margin.toFixed(0) +'">');
            };                       
        });
    });

    // 提交汇率修改
    $('.js-save-margin').click(function(){
        var ratelist = [];
        var feelist = [];

        var basic_tx_fee = $('#basic .tx-fee input').val();
        var silver_tx_fee = $('#silver .tx-fee input').val();
        var golden_tx_fee = $('#golden .tx-fee input').val();
        var platinum_tx_fee = $('#platinum .tx-fee input').val();

        val = basic_tx_fee           + '|' + 
                silver_tx_fee        + '|' + 
                golden_tx_fee        + '|' + 
                platinum_tx_fee;

        feelist.push(val); 

        $.each($('#basic .rate-table tbody tr'), function(i, item){
                var currency_type = $(item).attr('class');
                var val;
                var basic_bid_margin = $(item).children('.vip-bid').children('input').val();
                var basic_ask_margin = $(item).children('.vip-ask').children('input').val();
                var silver_bid_margin = $('#silver .rate-table tbody tr.'+currency_type+' .vip-bid input').val();
                var silver_ask_margin = $('#silver .rate-table tbody tr.'+currency_type+' .vip-ask input').val();
                var golden_bid_margin = $('#golden .rate-table tbody tr.'+currency_type+' .vip-bid input').val();
                var golden_ask_margin = $('#golden .rate-table tbody tr.'+currency_type+' .vip-ask input').val();
                var platinum_bid_margin = $('#platinum .rate-table tbody tr.'+currency_type+' .vip-bid input').val();
                var platinum_ask_margin = $('#platinum .rate-table tbody tr.'+currency_type+' .vip-ask input').val();
                                                
                val = currency_type         + '|' + 
                        basic_bid_margin    + '|' + 
                        basic_ask_margin    + '|' + 
                        silver_bid_margin   + '|' + 
                        silver_ask_margin   + '|' + 
                        golden_bid_margin   + '|' + 
                        golden_ask_margin   + '|' + 
                        platinum_bid_margin + '|' + 
                        platinum_ask_margin;

                ratelist.push(val);              
            });
        $.ajax({
            url : '/admin/rate/edit',
            type : 'post',
            dataType : 'json',
            data : {
                rate : ratelist,
                fee  : feelist
            },
            success : function(rep) {
                window.location.reload();
            }
        })
    });

    // 取消汇率修改
    $('.js-cancel-margin').click(function(){
        /*
        $('#rate-stop').val(0);
        $('.rate-add-action').show();
        $('.rate-edit-action').hide();

        $('#action-margin-panel').hide();
        $('#edit-margin-btn').show();        

        getQuotes();
        */
        window.location.reload();
    });

    // 显示基础汇率录入框
    $('#update-basic-rate-btn').click(function(){
        $('#basic-rate-tip').hide();
        $('#basic-rate-input').show();  
        $('#form-error-tip').html('');      
        $('#usdcny_rate').focus();
    });

    // 关闭基础汇率录入框
    $('#basic-rate-cancel-btn').click(function(){
        $('#basic-rate-input').hide();                        
        $('#basic-rate-tip').show();
    });

    // Basic rate 输入格式
    $('#usdcny_rate').mask("9.9999");   

    // 保存 basic rate
    $('#save-basic-rate-btn').click(function(){
        $.ajax({
            url : '/admin/rate/basic',
            type : 'POST',
            dataType : 'json',
            data : {usdcny_rate : $('#usdcny_rate').val()},
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'error'){
                    $('#form-error-tip').html(rep.data.msg);
                    return false;
                }
                
                $('#basic-rate-pannel').find('table>tfoot').show();
                getQuotes();
                getBasicRate();
                $('#form-error-tip').html('');
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    });

    // 移除基础汇率
    $(document).on('click', '.basic-remove-btn', function(){
        var obj = $(this);
        bootbox.confirm("Are you sure you want to delete?", function(result) {
            if (result) {
                $.ajax({
                    url : '/admin/rate/basic-remove',
                    type : 'POST',
                    dataType : 'json',
                    data : {
                        id : obj.closest('tr').attr('data-id')
                    },
                    success : function(rep) {
                        obj.closest('tr').remove();

                        getQuotes();
                    }
                })    
            }    
        })
    });

    //  查看更多基础汇率
    $('#more-btn').click(function(){
        getBasicRate(true);
    });

    // 获取汇率且每隔15秒刷新
    getQuotes();
    //window.setInterval("getQuotes()", 15000);

    // 载入基础汇率
    getBasicRate();
})

function getQuotes() {
    if ( ! tickList) return false;

    // 修改汇率时暂停刷新
    if ($('#rate-stop').val() == 1 || $('#rate-pannel').length == 0) return false;

    $.ajax({
        url: "/quote/index/market",
        data: "symbols=" + tickList,
        dataType: "json",
        beforeSend : function() {
            $.fancybox.showLoading();
        },
        success: quoteCallSucceed,
    })
}

/**
 * 获取汇率成功回调函数
 *
 * @param  rep
 * @return void
 */
function quoteCallSucceed(response) {
    $.fancybox.hideLoading();
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
        // 没有汇率数据
        if (tick.Bid == 0) {
            $('tr.' + tick.Symbol).find('.rate_change').html('');
            $('tr.' + tick.Symbol).find('.market-bid').html('');
            $('tr.' + tick.Symbol).find('.market-ask').html('');
            $('tr.' + tick.Symbol).find('.vip-bid').html('NO DATA');
            $('tr.' + tick.Symbol).find('.vip-ask').html('');
            $('tr.' + tick.Symbol).find('.time').html('');
            return;
        };

        // Wexchange margin
        var basicBid = Number(tick.Bid) + Number(margins[tick.Symbol].bidMargin);
        var basicAsk = Number(tick.Ask) + Number(margins[tick.Symbol].askMargin);
        // VIP margin
        var silverBid = Number(tick.Bid) + Number(margins[tick.Symbol].silverBidMargin);
        var silverAsk = Number(tick.Ask) + Number(margins[tick.Symbol].silverAskMargin);
        var goldenBid = Number(tick.Bid) + Number(margins[tick.Symbol].goldenBidMargin);
        var goldenAsk = Number(tick.Ask) + Number(margins[tick.Symbol].goldenAskMargin);
        var platinumBid = Number(tick.Bid) + Number(margins[tick.Symbol].platinumBidMargin);
        var platinumAsk = Number(tick.Ask) + Number(margins[tick.Symbol].platinumAskMargin);

        $('tr.' + tick.Symbol).find('.market-bid').html(tick.Bid.toFixed(4));
        $('tr.' + tick.Symbol).find('.market-ask').html(tick.Ask.toFixed(4));
        $('#basic .rate-table tbody tr.' + tick.Symbol).find('.vip-bid').html('<span>' + basicBid.toFixed(4) + '</span>');
        $('#basic .rate-table tbody tr.' + tick.Symbol).find('.vip-ask').html('<span>' + basicAsk.toFixed(4) + '</span>');
        $('#silver .rate-table tbody tr.' + tick.Symbol).find('.vip-bid').html('<span>' + silverBid.toFixed(4) + '</span>');
        $('#silver .rate-table tbody tr.' + tick.Symbol).find('.vip-ask').html('<span>' + silverAsk.toFixed(4) + '</span>');        
        $('#golden .rate-table tbody tr.' + tick.Symbol).find('.vip-bid').html('<span>' + goldenBid.toFixed(4) + '</span>');
        $('#golden .rate-table tbody tr.' + tick.Symbol).find('.vip-ask').html('<span>' + goldenAsk.toFixed(4) + '</span>');
        $('#platinum .rate-table tbody tr.' + tick.Symbol).find('.vip-bid').html('<span>' + platinumBid.toFixed(4) + '</span>');
        $('#platinum .rate-table tbody tr.' + tick.Symbol).find('.vip-ask').html('<span>' + platinumAsk.toFixed(4) + '</span>');

        if (tick.TickTime) {
            tick.TickTime = tick.TickTime.slice(11);
        };
        $('tr.' + tick.Symbol).find('.time').html(tick.TickTime);

        // 汇率升降标识
        var rate_change = $('tr.' + tick.Symbol + ' .rate_change');
        if (tick.UpDown == 1) {
            rate_change.removeClass('red').addClass('green').html('<i class="fa fa-arrow-up"</i>');
        }else{
            rate_change.removeClass('green').addClass('red').html('<i class="fa fa-arrow-down"</i>');
        };       
    }) 
 }

 /**
  * 载入基础汇率
  *
  * @param  bool 
  * @return void
  */
 function getBasicRate(append) {
    if ($('#basic-rate-pannel').length == 0) {
        return false;
    };
    $.ajax({
        url : '/admin/rate/basic',
        data : {perpage : $('#basic-rate-pannel').find('table>tbody>tr').length},
        success : function(rep) {
            if (rep == '') {
                $('#basic-rate-pannel').find('table>tfoot').hide();
                return false;
            }
            if (append) {
                $('#basic-rate-pannel').find('table>tbody').append(rep);
                return false;
            }

            $('#basic-rate-pannel').find('table>tbody').html(rep);
        }
    })
 }