$(function(){

    $("#invite-panel").validationEngine({
        promptPosition: "inline",
        maxErrorsPerField: 1,
        addFailureCssClassToField: "has-error"
    });

    $(document).on('click', '.invite-btn', function(){
        var self = $(this);
        $('#invite-panel').show();
        //$('#bank-buy,#bank-sell').removeClass('has-error');
        self.removeClass('invite-btn').addClass('cancel-btn').html('Cancel Invite');
    });   

    // 点击移除按钮
    $(document).on('click', '.cancel-btn', function(){
        $('#invite-panel').hide();
        $('.cancel-btn').removeClass('cancel-btn').addClass('invite-btn').html('Invite');
    }); 

    $(document).on('click', '#send-invite', function(){

        var uid = $(this).attr('data');
        var emails = $.trim($('#emails').val());

        $('#invite-error').html('');

        $.ajax({
            url : 'business/invite-business-member/' + uid,
            type : 'post',
            dataType : 'json',
            data : { emails : emails },
            success : function(rep) {
                if(rep.status == 'error')
                {
                    $('#invite-error').html(rep.data.msg);
                    $.fancybox.hideLoading();
                    return;
                }
                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);
    
            },
            beforeSend : function() {
                $.fancybox.showLoading();
            }
        })
    });
})