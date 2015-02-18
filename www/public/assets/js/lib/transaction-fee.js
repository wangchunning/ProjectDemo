$(function(){

	$("#transaction-fee-select").selectBoxIt({
        theme : 'bootstrap'
    });
    
    // 切换currency
    $('#transaction-fee-select').change(function(){
    	var currency = $(this).val();
    	var withdraw_currency = $(this).attr('data-currency');
    	var total_fee = $(this).attr('data-total-fee');

    	if (currency == withdraw_currency || currency == 'deposit')
    	{
    		$('#tx-fee-text').html('');
    		$('input[name=convert_tx_fee_amount]').val(total_fee);
    		$('input[name=convert_tx_fee_rate]').val(1);
    		
    		return;
    	}

	    $.ajax({
	        url: "/quote/convert",
	        data: {symbol : withdraw_currency + currency, need_lockrate : 0},
	        dataType: "json",
	        success: function(rep) {
	            $.fancybox.hideLoading();

	            if (!rep.length) return;

	            var rate = rep[0].Bid;
            	var convert_fee = total_fee * rate;
                $('#tx-fee-text').html(' ('+ convert_fee.toFixed(0) + ' ' + currency + ')');
                $('input[name=convert_tx_fee_amount]').val(convert_fee.toFixed(0));
                $('input[name=convert_tx_fee_rate]').val(rate);
        	},
	        error: function(result) {
	        },
	        beforeSend : function() {
	            $.fancybox.showLoading();
	        }
	    })

    });

    $('#transaction-fee-select').trigger('change');

});