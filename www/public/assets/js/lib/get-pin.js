$(function(){

    // 点击发送 sms pin
    $('#get-pin-btn').click(function()
    {
        var count = 20;
        var string = $(this).html(); 
        var obj = $(this);

        obj.attr('disabled', 'disabled');
 
        var id = setInterval(function() 
        {
            if (count === 1) 
            {
                clearInterval(id);  
            }           
            count--;
            obj.html(string + '(' + count + ')');
            if (count === 0)
            {
                obj.html(string).removeAttr('disabled');
            }

        }, 1000);

        $.ajax({
            url : '/lockrate/pin',
            dataType : 'json',
            success : function(rep) {
                if (rep.status == 'error') {
                    alert(rep.data.msg);
                    return false;
                }
                $('.mobile').html(rep.data.mobile);
                $('#confirm-sms-text').show();
            }
        })

    });

})