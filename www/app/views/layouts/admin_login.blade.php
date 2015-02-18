@include('partials.admin.header')

@yield('body-tag')
    <!-- header -->
    <div class="container">
        <div class="mt40 text-center">
           <a href="/">
                
            </a>
            
        </div>
    </div>
    <!-- /header -->

    @yield('content')

    @include('partials.admin.footer')

    @include('partials.admin.js')
</body>
</html>