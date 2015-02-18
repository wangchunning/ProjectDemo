@section('title')
Home
@stop

@section('body-tag')
<body class="home">
@stop

@section('content')

 <!--slogan-->
  <div class="container tcenter">
    <h1 class="big-slogan">Easier · Faster · Safer</h1>
    <h4 class="sm-slogan">You can now easily make a best FX deal you want.</h4>
  </div>
  <!-- /slogan-->

  <!-- page body-->
  <div class="container">
    <div class="main-body">
      <h2 class="deepred clearfix">Why you'll love using Wexchange<img src="./assets/img/credit-card.png" class="pull-right" /></h2>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6 tcenter"><img src="./assets/img/news-banner-1.jpg" /></div>
      </div>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6">
          <div class="pl20">
            <h3 class="deepred mt0">Open New Office</h3>
            <p class="lh30 mt20">
              Wexchange has opened a new Office in Docklands, Melbourne to market its physical presence in the northeast, expand its customer base. 
            </p>
            <p><a href="/coming-soon">Learn more »</a></p>
          </div>
        </div>
        <div class="col-sm-6 tcenter"><img src="./assets/img/new-banner-2.jpg" /></div>
      </div>
      
      <hr class="double-line mt20" />
      <div class="tcenter mt40 mb40">
        Start foreign exchange instantly &nbsp;&nbsp;&nbsp;&nbsp; <a href="/register" class="btn btn-lg btn-primary">Get Started With Wexchange</a>
      </div>
    </div>
  </div>
  <!-- /page body-->

@stop

@section('inline-js')
    {{ HTML::script('assets/js/lib/lock-rate.js') }}
    {{ HTML::script('assets/js/lib/home.js') }}    
@stop