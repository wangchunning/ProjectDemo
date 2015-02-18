<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">    
    <title>Coming soon</title>
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    @include('partials.public.css')   
</head>
<body>
    <!-- header -->
    <div class="container">
        <div class="header tcenter mt40">
            <a href="/">
                {{ HTML::image('assets/img/logo.png', NULL, array('width' => '276', 'height' => 62)) }}
            </a>
                {{ HTML::image('assets/img/national-grey.png', NULL, array('width' => '150', 'height' => 62)) }}
        </div>
    </div>
    <!-- /header -->
    <div class="container">
        <div class="header tcenter">
            <p class="f32p">We Are Currently Working On An <br>
                Awsome New Site, <b>STAY TUNED!</b>
            </p>
        </div>
    </div>
    <div class="container">
        <div class="footer tcenter">
            <ul class="list-unstyled list-inline">
                <li><a href="/">Home</a></li>
                <li><a href="/coming-soon">Contact</a></li>
                <li><a href="/privacy">Privacy</a></li>
                <li><a href="/legal">Legal Agreement</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
