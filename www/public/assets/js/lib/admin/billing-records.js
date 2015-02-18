  
$(function() {   

    // 实现点击表格可编辑
    $(document).on('click', '.editable', function() {   
      
        var objTD = $(this);   
        var oldText = objTD.html();
        var oldValue = objTD.find('input[type="hidden"]').val();
        
        var input = $("<input type='text' class='form-control' value='" + oldValue + "' />");   
          
        objTD.html(input);   
          
        input.click(function() {   
            return false;   
        });    
          
        input.select();   

        // 换汇金额限制只能输入数字
        $('input[type="text"]').numerical({
            decimal : true
        });        
          
        // 文本框失焦后处理
        input.blur(function() {   
              
            var newText = $(this).val();   
              
            objTD.html(oldText);  

            objTD.find('span').html(newText);
            objTD.find('input[type="hidden"]').val(newText);
        });
    });

    // 点击添加 rate 按钮
    $('#add-rate-btn').on('click', function() {
        $(this).hide();
        $('#add-rate-panel').removeClass('hidden'); 
    });

    // 点击退出按钮
    $('#cancel-btn').on('click', function() {
        $('#add-rate-btn').show();
        $('#add-rate-panel').addClass('hidden');         
    });


    /**
     * init currency_to filter
     * 根据currency_from及table中已经添加的货币对，设置currency_to
     * 
     */
    currency_filter('currency_from', 'currency_to', 'billing-confirm-deals');
    /**
     * currency_to filter
     * 根据currency_from及table中已经添加的货币对，设置currency_to
     * 
     */
    $('#currency_from').on('change', function() {
        
        currency_filter('currency_from', 'currency_to', 'billing-confirm-deals');

    });

    // 点击保存按钮
    $('#save-rate-btn').on('click', function() {
        var obj = $('#add-rate-panel');
        var currency = obj.find('select[name="currency_from"]').val() + obj.find('select[name="currency_to"]').val()

        $.ajax({
            url : '/admin/billing/to-records',
            type : 'POST',
            dataType : 'json',
            data : { currency : currency },
            success : function(resp) {
                $.fancybox.hideLoading();

                $('.billing-confirm-deals tbody').append(
                    '<tr>' + 
                    '<td>' + resp.data.currency + '<input type="hidden" name="currencies[]" value="' + currency + '"></td>' + 
                    '<td class="editable"><span>0</span> <input type="hidden" name="rates[]"></td>' + 
                    '<td class="editable"><span>0.00</span> <input type="hidden" name="buys[]">' + resp.data.from + '</td>' + 
                    '<td class="editable"><span>0.00</span> <input type="hidden" name="sells[]">' + resp.data.to + '</td>' +                            
                    '<td><a href="javascript:;" data-currency="' +  currency + '" class="remove-btn"><i class="fa fa-minus-circle"></i></a></td>' +                     
                    '</tr>'
                );

                $('#cancel-btn').trigger('click');
                /**
                 * flash currency_to options
                 */
                currency_filter('currency_from', 'currency_to', 'billing-confirm-deals');
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    });

    // 点击移除按钮
    $(document).on('click', '.remove-btn', function(){
        var self = $(this);
        $.ajax({
            url : '/admin/billing/records-remove',
            type : 'POST',
            dataType : 'json',
            data : { currency : self.attr('data-currency') },
            success : function() {
                $.fancybox.hideLoading();

                self.closest('tr').remove();
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    })   
});

/**
 * currency_to filter
 * 根据currency_from及table中已经添加的货币对，设置currency_to
 *
 * from_id - currency_from
 * to_id   - currency_to
 * table_id - billing table
 * 
 */
function currency_filter(from_id, to_id, table_id){
    
    var _from_obj = $('#' + from_id);	// from object
    var _to_obj   = $('#' + to_id);		// to object
    var _tab_obj  = $('#' + table_id);	// table object

    var _selected_cur = _from_obj.find('option:selected').attr('value');

    /**
     * get all currencise from `from_object`
     */
    var _all_currencies = {};
    _from_obj.children("option").each(function(){

        var _currency = $(this).attr('value');

        if (_selected_cur != _currency)
        {
	        _all_currencies[_currency] = $(this).clone();
        }

    });

    /**
     * 从all_currencies中删除已经在table中存在的货币对
     *
     * slice(1) - skip th
     */
    _tab_obj.find('tr').slice(1).each(function(){

        var _cur_text = $.trim($(this).children("td:first").text());
        var _cur_from = _cur_text.substr(0, 3);
        var _cur_to   = _cur_text.substr(4);

        if (_cur_from == _selected_cur) {
            delete _all_currencies[_cur_to];    
        }
    });

    /**
     * make options of currency_to
     */
    _to_obj.find('option').remove();

    $.each(_all_currencies, function(currency, obj_cur){

        _to_obj.append(obj_cur);
    });

} 

