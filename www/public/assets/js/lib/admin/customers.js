$(function(){

    // 重发激活邮件
    $('#resend-email-btn').click(function() {
        var self = $(this);
        $.fancybox.showLoading();
        $.ajax({
            url : '/admin/customers/resend-verify-email',
            dataType : 'json',
            data : {uid : self.attr('data-uid')},
            type : 'POST',
            success : function(rep) {
                $.fancybox.hideLoading();
                $('#handingMsg').html('<div class="alert alert-success">' + rep.data.msg + '</div>');                    
            }
        })        
    });

    // 发送重置密码邮件
    $('#reset-password').click(function() {
        var self = $(this);
        $.fancybox.showLoading();
        $.ajax({
            url : '/admin/customers/reset-password-email',
            dataType : 'json',
            data : {uid : self.attr('data-uid')},
            type : 'POST',
            success : function(rep) {
                $.fancybox.hideLoading();
                $('#handingMsg').html('<div class="alert alert-success">' + rep.data.msg + '</div>');                    
            }
        })  
    });

    // 点击验证按钮
    $('.verify-btn').click(function(){
        var btn = $(this);
        $.ajax({
            url : '/admin/customers/verify',
            type : 'POST',
            dataType : 'json',
            data : {
                uid : $('#verify-pannel').attr('data-uid'),
                action : btn.attr('data-action'),
                type : btn.attr('data-type')
            },
            success : function() {
                window.location.reload();
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    });

    // 删除用户
    $('.user-remove').click(function(){
        var url = $(this).attr('href');
        $.ajax({
            url : url,
            type : 'GET',
            dataType : 'json',
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    return false;
                }
                window.location.reload();
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
        return false;
    });

    // 点击显示用户认证信息
    $('#show-verify').click(function(){
        $('#verify-pannel').slideDown();
        $(this).hide();        
    });


    // 点击验证按钮
    $('#remind-inactive-btn').click(function(){
        var btn = $(this);
        var uid = btn.attr('data-uid');

        $.ajax({
            url : '/admin/customers/remind-inactive-customer/' + uid,
            type : 'post',
            dataType : 'json',
            success : function(rep) {
                
                if (rep.status == 'error') {
                    alter(rep.data.msg);
                    $.fancybox.hideLoading();
                    return;
                };

                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);

            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    });

});