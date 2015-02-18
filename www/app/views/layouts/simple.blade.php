@include('partials.public.header')

@yield('body-tag')
    <!-- header -->
    <div class="container mb20">
        <div class="pt20 pb20 border-2b">
            @if (is_login() AND ! sessionTimeout())
            <div class="btn-group pull-right top-right-name">
                <a class="color-737 f18p custom-tooltips" href="/profile" 
                	data-container="body" 
                	data-toggle="tooltip" 
                	data-original-title="我的账户">
                	<span class="fa fa-home"></span>
                </a>
                <a class="color-737 f18p ml10 mr5 custom-tooltips" href="/logout" 
                	data-container="body"  
                	data-toggle="tooltip" 
                	data-original-title="注销">
                	<span class="fa fa-power-off"></span>
                </a>
            </div>
            @endif
            <a href="/">
                {{ HTML::image('assets/img/logo.png', NULL) }}
            </a>
                
        </div>  
    </div>
    <!-- /header -->

    @yield('content')

    @include('partials.public.footer')

    @include('partials.public.js')
</body>
</html>