$(function(){

    // 点击发送邮件提醒
    $('#email-reminder').on('click', function(){
        var tran_id = $(this).attr('data-id');
        $.ajax({
            url : '/admin/transaction/reminder/email_reminder/' + tran_id,
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    alert(rep.data.msg);
                    return false;
                } 
                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);         
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })          
    });

    // 初始银行选择框
    var $bank_select = $('#bank-select').selectize({valueField : 'value', sortField:'order'});
    var $currency_select = $('#currency-select').selectize({valueField : 'value'});

    // 金额限制只能输入数字
    $('input.number-only').numerical({
        decimal : true
    });

    $('#new-amount').change(function(){
        var max_amount = $('#max-amount').val();
        // 输入值不大于差值
        if (parseFloat($(this).val()) > parseFloat(max_amount)) {
            $(this).val(max_amount);
        };
    });

    // 点击添加 bank 按钮
    $('.bank-panel').delegate('#add-amount', 'click', function() {
        var tran_id = $(this).attr('data-id');
        var bank_selectize = $bank_select[0].selectize;
        $('#new-amount').val($('#max-amount').val());
        // 清除下拉列表
        bank_selectize.clearOptions();

        $.ajax({
            url : '/admin/transaction/bank-selecter/' + tran_id,
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'ok') {
                    $.each(rep.data.data, function(key, val){
                       bank_selectize.addOption(val);
                    });
                    //bank_selectize.addOption(rep.data.data);
                };
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
        $(this).hide();
        $('#add-bank-panel').removeClass('hidden'); 
    });

    // 选择银行后，自动填充货币
    $('#bank-select').change(function() {
        // 清除下拉列表
        var currency_selectize = $currency_select[0].selectize;
        currency_selectize.clearOptions();

        var bank_id = $('#bank-select').val();
        $.ajax({
            url : '/admin/bank/bank-currency/' + bank_id,
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'ok') {
                    currency_selectize.addOption(rep.data.data);
                };
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    });

    // 点击退出按钮
    $('#cancel-btn').on('click', function() {
        $('#add-amount').show();
        $('#add-bank-panel').addClass('hidden');         
    });

    // 点击保存按钮
    $('#save-bank-btn').on('click', function() {
        var obj = $('#add-bank-panel');
        var tran_id = obj.attr('data-id');
        var bank_id = $('#bank-select').val();
        var currency = $('#currency-select').val();
        var amount = Number($('#new-amount').val()).toFixed(2);

        $.ajax({
            url : '/admin/transaction/bank-add/' + tran_id,
            type : 'POST',
            dataType : 'json',
            data : { bank_id : bank_id, amount : amount, currency : currency },
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'error') {
                    alert(rep.data.msg);
                    return;
                };
                var actions = rep.data.info.actions ? '<div class="actions"><a class="bank-edit-btn fa fa-pencil" title="Edit" href="javascript:;"></a><a class="bank-remove-btn fa fa-minus-circle" title="Remove" href="javascript:;"></a></div>' : '';
                $('.bank-panel tbody').append(
                    '<tr class="' + rep.data.info.item_id +'" data-id="' + tran_id + '">' + 
                    '<td class="no-padding">' + actions + '</td>' +
                    '<td>' + rep.data.info.account_number + '</td>' + 
                    '<td>' + rep.data.info.bank_name + '</td>' +
                    '<td>' + rep.data.info.balance + '</td>' +
                    '<td>' + rep.data.info.currency + '</td>' +
                    '<td class="editable">' + rep.data.info.amount + '</td>' +
                    '</tr>'
                );

                // 修改可输入的最大值
                $('#max-amount').val(rep.data.info.max_amount);
                if (rep.data.info.max_amount == 0) {
                    $('#add-amount').remove();
                };
                
                $('#cancel-btn').trigger('click');

            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    });
    
    // 点击修改按钮
    $('.bank-panel').delegate('a.bank-edit-btn', 'click', function(){
        var obj = $(this).parents('tr');
        var item_id = obj.attr('class');
        var tran_id = obj.attr('data-id');
        var objTD = obj.find('.editable');
        var oldValue = objTD.text();
        var input = $("<input type='text' class='form-control' value='" + oldValue + "' />");  
        objTD.html(input);

        // 换汇金额限制只能输入数字
        obj.find('input[type="text"]').numerical({
            decimal : true
        });

        // 文本框失焦后处理
        input.blur(function() {           
            var new_val = Number($(this).val()).toFixed(2);
            // 值并未改变
            if (new_val == oldValue) {
                objTD.html(oldValue);
                return;
            };   
            $.ajax({
                url : '/admin/transaction/bank-edit/' + tran_id,
                type : 'POST',
                dataType : 'json',
                data : { item_id : item_id, amount : new_val },
                success : function(rep) {
                    $.fancybox.hideLoading();
                    if (rep.status == 'error') {
                        alert(rep.data.msg);
                        objTD.html(oldValue);
                        return;
                    };
                    
                    objTD.html(rep.data.info.amount);
                    // 修改可输入的最大值
                    $('#max-amount').val(rep.data.info.max_amount);
                    if (rep.data.info.max_amount == 0) {
                        $('#add-amount').remove();
                    }else if($('#add-amount').length == 0){
                        var add_html = '<span class="btn btn-default" data-id="' + tran_id + '" id="add-amount"> Add Amount </span>';
                        $('#add-bank-panel').before(add_html);
                    };
                    $('#cancel-btn').trigger('click');
                },
                beforeSend : function() {
                    $.fancybox.showLoading();
                }
            })
        });     
    });

    // 点击删除按钮
    $('.bank-panel').delegate('a.bank-remove-btn', 'click', function(){
        var obj = $(this).parents('tr');
        var item_id = obj.attr('class');
        var tran_id = obj.attr('data-id');
        $.ajax({
            url : '/admin/transaction/bank-remove/' + tran_id + '/' + item_id,
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'error') {
                    alert(rep.data.msg);
                    return;
                };
                obj.remove();
                // 修改可输入的最大值
                $('#max-amount').val(rep.data.info.max_amount);
                if (rep.data.info.max_amount == 0) {
                    $('#add-amount').remove();
                }else if($('#add-amount').length == 0){
                    var add_html = '<span class="btn btn-default" data-id="' + tran_id + '" id="add-amount"> Add Amount </span>';
                    $('#add-bank-panel').before(add_html);
                };
                $('#cancel-btn').trigger('click');
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    });
})