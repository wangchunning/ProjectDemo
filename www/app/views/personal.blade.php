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
        <div class="col-sm-6 tcenter"><img src="./assets/img/personal-banner-1.jpg" /></div>
        <div class="col-sm-6">
          <h3 class="deepred mt0">Migration</h3>
          <p class="lh30 mt20">
            Migrating or moving overseas is a big undertaking with many important things to organise. 
            Wexchange will provide the great start in your new country.
          </p>
        </div>
      </div>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6">
          <div class="pl20">
            <h3 class="deepred mt0">Overseas Investments</h3>
            <p class="lh30 mt20">
              Are you paying excessive fees or receiving uncompetitive exchange rates when you purchase foreign currencies to invest?
              Wexchange can help ensure that more of your money arrives where it should by giving you an excellent exchange rate and taking a low (or no!) fee. 
            </p>
            <p><a href="/coming-soon">Learn more »</a></p>
          </div>
        </div>
        <div class="col-sm-6 tcenter"><img src="./assets/img/personal-banner-2.jpg" /></div>
      </div>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6 tcenter"><img src="./assets/img/personal-banner-3.jpg" /></div>
        <div class="col-sm-6">
          <h3 class="deepred mt0">Study Oversea</h3>
          <p class="lh30 mt20">
            When you go to Australia and begin your new study. you must need foreign currency exchange, no one will make the process easier, faster and less costly than Wexchange.  Our foreign exchange rates are unbeatable and the service we offer is amazing. Start now!!</p>
          <p><a href="/coming-soon">Learn more »</a></p>
        </div>
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