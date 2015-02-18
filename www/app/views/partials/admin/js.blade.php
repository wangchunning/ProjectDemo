    {{ HTML::script('assets/js/vendor/jquery-1.11.1.min.js') }}
    {{ HTML::script('assets/jquery-ui/jquery-ui.min.js') }} 
    {{ HTML::script('assets/js/vendor/modernizr-2.6.2.min.js') }}    
	<!--[if lte IE 6]>
	 {{ HTML::script('assets/js/vendor/bootstrap-ie.js') }}
	<![endif]-->
	{{ HTML::script('assets/js/vendor/bootstrap.min.js') }}
    {{ HTML::script('assets/fancybox/jquery.fancybox.pack.js') }}
    {{ HTML::script('assets/fancybox/helpers/jquery.fancybox-buttons.js') }}
    {{ HTML::script('assets/js/vendor/bootbox.min.js') }} 
    {{ HTML::script('assets/js/vendor/bootstrap-datetimepicker.js') }}   
    {{ HTML::script('assets/js/vendor/bootstrap-datetimepicker.zh-CN.js') }}    
    
        
    {{ HTML::script('assets/selectboxit/jquery.selectBoxIt.min.js') }}  
    {{ HTML::script('assets/js/vendor/alexwolfe-buttons1.js') }}    
    {{-- HTML::script('assets/js/vendor/jquery-numeric.js') --}}
    {{ HTML::script('assets/js/vendor/bootstrapValidator.min.js') }}
    {{ HTML::script('assets/js/vendor/bootstrapValidator_zh_CN.js') }}
    {{ HTML::script('assets/js/vendor/icheck.min.js') }}
    {{ HTML::script('assets/js/vendor/jquery.dataTables.min.js') }}
    {{ HTML::script('assets/plupload/plupload.full.min.js') }}
	{{ HTML::script('assets/plupload/jquery.plupload.queue/jquery.plupload.queue.min.js') }}
	{{ HTML::script('assets/plupload/i18n/zh_CN.js') }}
	
	{{ HTML::script('assets/js/vendor/summernote.min.js') }}
	{{ HTML::script('assets/js/vendor/summernote-zh-CN.js') }}
	
    {{ HTML::script('assets/js/common.js') }} 
    {{ HTML::script('assets/js/main.js') }}    
    {{ HTML::script('assets/js/data_table.js') }}
	{{ HTML::script('assets/js/confirm_modal.js') }}
	
    @yield('inline-js')
