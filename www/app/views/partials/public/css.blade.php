    {{ HTML::style('assets/css/bootstrap.min.css') }}
    <!--[if lte IE 6]>
	{{ HTML::style('assets/css/bootstrap-ie6.min.css') }}   
	{{ HTML::style('assets/css/bootstrap-ie.css') }}   
	<![endif]-->
    {{ HTML::style('assets/css/bootstrap-theme.min.css') }}
    {{ HTML::style('assets/css/font-awesome.min.css') }}
    {{ HTML::style('assets/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('assets/fancybox/jquery.fancybox.css') }}
    {{ HTML::style('assets/fancybox/helpers/jquery.fancybox-buttons.css') }}
    {{ HTML::style('assets/selectboxit/jquery.selectBoxIt.css') }}  
	{{ HTML::style('assets/css/alexwolfe-buttons1.css') }} 
	{{ HTML::style('assets/css/bootstrapValidator.min.css') }} 
	{{ HTML::style('assets/icheck/blue.css') }} 

	{{ HTML::style('assets/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css') }}
	
	{{ HTML::style('assets/css/normalize.css') }}  
	{{ HTML::style('assets/css/main.css') }}    
	{{ HTML::style('assets/css/common.css') }}     
    {{ HTML::style('assets/css/custom.css') }}    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ HTML::script('assets/js/vendor/html5shiv.js') }}
    {{ HTML::script('assets/js/vendor/respond.min.js') }}  
    <![endif]-->
  
  
    @yield('inline-css')