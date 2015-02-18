@section('title')
我要投资
@stop

@section('content')
@include('breadcrumb')
<div class="container ">
    <div class="flat-panel">

        <h4>可投资项目</h4>
        <div class="table-responsive">
            <table class="table table-hover table-striped mt20">
                <thead>
                    <tr>
                        <th>企业名称</th>
                        <th>拟贷款金额</th> 
                        <th>贷款申请期限</th>
                        <th>抵质押方式</th>
                        <th>担保方式</th>                           
                        <th>状态</th>
                    </tr>
                </thead>
                <tbody> 
                    @if ($business_finance->isEmpty())
                    <tr><td colspan="7" align="center">- 没有记录 -</td></tr>
                    @else
                        @foreach ($business_finance as $item)
                        <tr onclick="window.location.href=('{{ url('/invest/apply-business-financing', $item->id) }}')" style="CURSOR:pointer">
                            <td>{{ $item->business_name }}</td>
                            <td>{{ $item->expect_loan_amount }}</td>
                            <td>{{ $item->loan_during }}</td>
                            <td>{{ $item->pledge }}</td>
                            <td>{{ $item->guarantee }}</td>
                            <td>{{ $item->status_desc }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
  


    </div>
</div>
@stop

@section('inline-js')

@stop