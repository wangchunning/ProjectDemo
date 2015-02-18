@section('title')
首页 - 天添财富
@stop

@section('body-tag')
<body id="home">
@stop

@section('content')
<div class="container">

<!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide mt20" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <a href='#'><img src="{{url('assets/img/home-bg.jpg')}}" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Example headline.</h1>
              <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
            </div>
          </div></a>
        </div>
        <div class="item">
          <img src="{{url('assets/img/home-bg.jpg')}}" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Another example headline.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="{{url('assets/img/home-bg.jpg')}}" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>One more for good measure.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->

    <hr class="mt40 mb10">

    <div class="row">
        <div class="col-sm-4">
            <h4 class="mt20 mb20">我们的使命</h4>
            <p>
                天添财富帮助中小企业快速建立融资渠道，让借贷过程变得更加有效、透明。
                与传统银行相比，我们的运营成本更低，所以我们能够给与借款方更低的利率，
                同时也会使投资者得到满意的收益回报。
            </p>
        </div>
        <div class="col-sm-8 pl0">
            {{ HTML::image('/assets/img/borrow_step_1.png', NULL, array('width' => '150')) }}
            {{ HTML::image('/assets/img/borrow_step_2.png', NULL, array('width' => '150')) }}
            {{ HTML::image('/assets/img/borrow_step_3.png', NULL, array('width' => '150')) }}
            {{ HTML::image('/assets/img/borrow_step_4.png', NULL, array('width' => '150')) }}
            
        </div>
    </div>

</div>

@stop

@section('inline-js')
  

<script>
$(function(){
    
    $("select").selectBoxIt({
        theme : 'bootstrap',
        autoWidth: false
    });
    
	$('.carousel').carousel({'interval' : 5000});
})

</script>
@stop