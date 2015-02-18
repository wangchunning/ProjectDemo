$(function() {
    // 点击添加按钮
    $(document).on('click', '.add-btn', function(){
        var self = $(this);
        var currency_pair = self.attr('data-currency');

        //var base_currency  = currency_pair.substring(0, 3);
        //var quote_currency = currency_pair.substring(3, 3);
        //var base_currency_diff  = self.attr('data-base-currency-diff');
        //var quote_currency_diff = self.attr('data-quote-currency-diff');

        $('#record-deal-tr').remove();
        $('.remove-btn').removeClass('remove-btn').addClass('add-btn').html('Record Deal');
        /**
         * make form
         */
        form = $("<form class='form-inline mb10' id='record-deal-form'></form>");
        form.attr('action', '/admin/billing/records');
        form.attr('method','post');
        label_curr = $("<label class='col-xs-1 mr10 mt5 pl0'>"+ currency_pair +"</label>");
        input_dw   = $("<input type='hidden' name='dw' value='" + $.trim($('#date_word_button').text()) + "'>");
        input_st   = $("<input type='hidden' name='status' value='" + $.trim($('#status_button').text()) + "'>");
        input_start = $("<input type='hidden' name='start' value='" + $.trim($('#start').attr('value')) + "'>");
        input_expiry = $("<input type='hidden' name='expriy' value='" + $.trim($('#expriy').attr('value')) + "'>");
        input_curr = $("<input type='hidden' name='currency-pair' value='" + currency_pair + "'>");
        input_rate = $("<div class='col-xs-3'><input id='bank-rate' class='form-control validate[required]' type='text' name='rate' placeholder='Rate' /></div>");
        input_buy  = $("<div class='col-xs-3'><input id='bank-buy'  class='form-control validate[required]' type='text' name='buy'  placeholder='Bank Buy Amount' /></div>");
        input_sell = $("<div class='col-xs-3'><input id='bank-sell' class='form-control validate[required]' type='text' name='sell' placeholder='Bank Sell Amount'/></div>");
        submit = $("<button type='submit' class='btn btn-primary'>Save Deal</button>");
        
        form.append(label_curr)
            .append(input_dw)
            .append(input_st)
            .append(input_start)
            .append(input_expiry)
            .append(input_curr)
            .append(input_rate)
            .append(input_buy)
            .append(input_sell)
            .append(submit);

        tr = $("<tr id='record-deal-tr'></tr>");
        td = $("<td colspan=6></td>");
        td.append(form);
        tr.append(td);

        $(this).closest('tr').after(tr);

        self.removeClass('add-btn').addClass('remove-btn').html('Cancel Record');
        //form.submit();

        /*
        $.ajax({
            url : '/admin/billing/records',
            type : 'POST',
            dataType : 'json',
            data : { currency : self.attr('data-currency') },
            success : function() {
                self.removeClass('add-btn').addClass('remove-btn').html('Cancel');
                $.fancybox.hideLoading();

                
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
        */
    });

    /* 只能输入数字 */
    $('table').delegate('#bank-rate,#bank-buy,#bank-sell','focus',function(){
        $(this).numerical({
            decimal : true
        });
    });
    /* 当 rate 输入值后，去掉错误提示信息 */
    $('table').delegate('#bank-rate','keyup',function(){
        $(this).removeClass('has-error');
        $(this).next('.formError').remove();
    }); 

    /* bank-buy, bank-sell, 有一个有值，就去掉彼此错误提示信息 */
    $('table').delegate('#bank-buy,#bank-sell','keyup',function(){
        $('#bank-buy,#bank-sell').removeClass('has-error');
        $('#bank-buy,#bank-sell').next('.formError').remove();
    });

    $("table").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });  

    /* 根据rate，自动填充 bank-buy, bank-sell */
    $('table').delegate('#bank-buy','keyup',function(){
        var amount = parseFloat($(this).val()) * parseFloat($('#bank-rate').val());
        $('#bank-sell').val(isNaN(amount) ? '' : amount.toFixed(2));   
    });
    $('table').delegate('#bank-sell','keyup',function(){
        var amount = $('#bank-rate').val() == 0 ? 0 : 
                    parseFloat($(this).val()) / parseFloat($('#bank-rate').val());
        $('#bank-buy').val(isNaN(amount) ? '' : amount.toFixed(2));
    });    

    // 点击移除按钮
    $(document).on('click', '.remove-btn', function(){
        var self = $(this);
        $('#record-deal-tr').remove();
        $('.remove-btn').removeClass('remove-btn').addClass('add-btn').html('Record Deal');
        /*
        $.ajax({
            url : '/admin/billing/records-remove',
            type : 'POST',
            dataType : 'json',
            data : { currency : self.attr('data-currency') },
            success : function() {
                $.fancybox.hideLoading();

                self.removeClass('remove-btn').addClass('add-btn').html('Record Deal');
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
        */
    }); 
})