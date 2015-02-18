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
        <div class="col-sm-6 tcenter"><img src="./assets/img/business-banner-1.png" /></div>
        <div class="col-sm-6">
          <h3 class="deepred mt0">International Payments</h3>
          <p class="lh30 mt20">
            Our range of corporate solutions can provide your business with a simple and cost effective way of making international payments, and receiving incoming funds.
            <ul>
              <li>Incoming and outgoing payments</li>
              <li>Access to dedicated FX specialists</li>
              <li>Risk Management Tools</li>
            </ul>
          </p>
        </div>
      </div>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6">
          <div class="pl20">
            <h3 class="deepred mt0">Small Business</h3>
            <p class="lh30 mt20">
              Our online FX solutions for small businesses can help you make payments to international suppliers, without ever having to leave the office.
            </p>
            <p><a href="/coming-soon">Learn more »</a></p>
          </div>
        </div>
        <div class="col-sm-6 tcenter"><img src="./assets/img/business-banner-2.png" /></div>
      </div>
      <hr class="double-line" />
      <div class="row mt40 mb40">
        <div class="col-sm-6 tcenter"><img src="./assets/img/business-banner-3.png" /></div>
        <div class="col-sm-6">
          <h3 class="deepred mt0">Industry Solutions</h3>
          <p class="lh30 mt20">
            We understand that certain industries have unique requirements when it comes to foreign exchange . For this reason, we offer a verity of specialised solutions that are tailor-made for your industry.
          </p>
          <p>
            With custom built solutions for NGOs, educational institutions, law firms, financial institutions, and more, we can help you to manage your international foreign exchange needs
          </p>
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