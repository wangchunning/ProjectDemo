$(function(){

    $('#new-bank-log-input').hide();  
    // 
    $('#new-bank-log-btn').click(function(){
        $('#new-bank-log-tip').hide();
        $('#new-bank-log-input').show();  
        $('#form-error-tip').html('');      
        $('#new-bank-log-input input[name=new_bank_log_amount]').focus();
    });

    // 
    $('#new-bank-log-cancel-btn').click(function(){
        $('#new-bank-log-input').hide();                        
        $('#new-bank-log-tip').show();
    });

    // 输入格式
    $('#new-bank-log-input input[name=new_bank_log_amount]').numerical({
            decimal : true
        });  

    $('#new-bank-log-input select[name=new_bank_log_type]').selectBoxIt({
        theme : 'bootstrap',
        autoWidth : false
    });
    // 
    $('#new-bank-log-save-btn').click(function(){

        var bank_id = $(this).attr('data-id');
        var currency = $(this).attr('data-currency');
        var type = $('#new-bank-log-input select[name=new_bank_log_type]').val();
        var amount = $('#new-bank-log-input input[name=new_bank_log_amount]').val();
        var desc = $('#new-bank-log-input input[name=new_bank_log_desc]').val();

        $.ajax({
            url : '/admin/bank/add-bank-log/' + bank_id + '/' + currency,
            type : 'POST',
            dataType : 'json',
            data : {type : type, amount : amount, desc : desc},
            success : function(rep) {
                $.fancybox.hideLoading();
                if (rep.status == 'error'){
                    $('#form-error-tip').html(rep.data.msg);
                    return false;
                }
                
                window.location.reload();
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    });


})