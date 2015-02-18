$(function(){ 

    $('.fund-btn').click(function(e){
		e.preventDefault();

		if ($("#amount").val() == '' ||
				$('#frm .has-error').length > 0)
		{
			alert('请输入有效金额');
			return;
		}
		
        var _args = {	
                	'amount'		: $("#amount").val(), 
                	'real_amount'	: $('#real_amount').text(), 
                	'fee_amount'	: $('#fund_fee').text() 
                };
        
    	openPostWindow('/fund/add', _args, '完成充值 - 天添财富');

    	confirm_text 	= '已完成付款';
    	cancel_text 	= '付款遇到问题';

    	bootbox.setDefaults({ locale: "zh_CN" });

    	bootbox.dialog({
    		message: "付款完成前请不要关闭此窗口。完成付款后请根据您的情况点击下面的按钮：",
    		title: '请您在新打开的网上银行页面上完成付款',
    		buttons: {
    					main: {
    						 label: confirm_text,
    						 className: "button button-rounded button-flat-primary",
    						 callback: function() {
    							 window.location.href = '/home';
    						 }
    					 },
    					 danger: {
    						 label: cancel_text,
    						 className: "button button-rounded button-flat-caution"
    					 }
    				 }
		});
    });

});
