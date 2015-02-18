
function init_confirm_modal(btn_class, title, submit_type, frm) 
{  
	submit_type 	= submit_type || 'href';
	frm 			= frm || '';
	
	$('.' + btn_class).click(function(e){
		//e.preventDefault();
		
		var obj = $(this);
		
		bootbox.setDefaults({ locale: "zh_CN" });
		
		bootbox.confirm(title, function(result) {
			if (result) {
				if (submit_type == 'href')
				{
					window.location.href = obj.attr('href');
				}
				else
				{
					$(frm).submit();
				}
				
			}
		});			

		return false;
	});
         

} // function

