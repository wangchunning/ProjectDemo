@section('title')
Home
@stop

@section('body-tag')
<body id="home">
@stop

@section('content')
@include('breadcrumb')
<div class="container">
    <div class="flat-panel">
    
        <h4>理财产品</h4>
        <div class="table-responsive">
            <table class="table table-hover table-striped mt20" id="my_tx_history_table">
                <thead>
                    <tr>
                        <th>项目名称</th>
                        <th>预期年化利率/收益率</th>
                        <th>募集期</th> 
                        <th>起售金额</th>                            
                        <th>风险等级</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($licai as $item)
                    <tr onclick="window.location.href=('{{ url('/') }}')" style="CURSOR:pointer">
                        <td> {{ $item->title }} </td>
                        <td> {{ $item->expect_max_return_rate }} %</td>
                        <td> {{ $item->raise_start_date . ' - ' . $item->raise_end_date}} </td>
                        <td> {{ $item->min_buy_amount }} 元</td>
                        <td> {{ $item->risk_level }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

    $('#nav-vip a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    })

})

</script>
@stop