$(function(){ 

    $('.withdraw-btn').click(function(e){
		e.preventDefault();

		if ($("#amount").val() == '' ||
				$("#password").val() == ''
				$('#frm .has-error').length > 0)
		{
			alert('请输入有效金额及交易密码');
			return;
		}

    	bootbox.setDefaults({ locale: "zh_CN" });

		bootbox.confirm(title, function(result) {
			if (result) {
				$(this).submit();
			}
		});
		
		return false;
    });

});
