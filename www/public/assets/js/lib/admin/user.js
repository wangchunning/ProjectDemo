$(function(){

    // 点击提交按钮
    $('#invite-submit').click(function(){
        var selectedItems = new Array();
        $("input[name='roles[]']:checked").each(function() {selectedItems.push($(this).val());});
        $.ajax({
            url : '/admin/users/invite',
            type : 'POST',
            dataType : 'json',
            data : {
                email : $('#email').val(),
                roles : selectedItems.join('|')
            },
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    $('#form-error-tip').html(rep.data.msg);
                    return false;
                }
                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                $('#form-error-tip').html('');
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    })

    // 点击重发邀请邮件
    $('#invite-panel .resend-invite').click(function(){
        var obj = $(this);
        $.ajax({
            url : obj.attr('href'),
            type : 'GET',
            dataType : 'json',
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    return false;
                }
                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                obj.parent('td').prev('td.expires').html(rep.data.expires);
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
        return false;
    })

    // 切换用户状态
    $('.manager-status').change(function(){
        var status = $(this).val();
        $.ajax({
            url : '/admin/profile/change-status/' + status,
            type : 'GET',
            dataType : 'json',
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    return false;
                }
                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    })

    // 删除用户
    $(document).on('click', '.user-remove', function(){
        var obj = $(this);
        bootbox.confirm("Are you sure you want to delete?", function(result) {
            if (result) {
                var url = obj.attr('href');
                $.ajax({
                    url : url,
                    type : 'GET',
                    dataType : 'json',
                    success : function(rep) {
                        if (rep.status == 'error'){
                            alert(rep.data.msg);
                            $.fancybox.hideLoading();
                            return false;
                        }
                        window.location.reload();
                    },
                    beforeSend : function(){
                        $.fancybox.showLoading();
                    }
                })    
            }    
        })
        return false;
    });
})