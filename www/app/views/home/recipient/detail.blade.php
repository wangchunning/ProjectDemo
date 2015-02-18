@section('title')
收款人详细信息
@stop

@section('content')
    @include('home.subnav')
    @include('breadcrumb')
    <div class="container flat-panel">
        <div class="dashboard-body">
            <h2><i class="fa fa-user"></i>收款人详细信息</h2>
            <div id="recipient-details-pannel">          
                <div class="ml0 mr0">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="f32p color-f90">{{ $recipient->account_name }} <span class="f16p ml15"><a href="{{ URL::to('recipient/edit/' . $recipient->id) }}" class="underline">编辑</a><a href="{{ URL::to('recipient/del/' . $recipient->id) }}" class="del-btn underline ml10">删除</a></span></h2>
                            <span class="help-block">{{ $recipient->transfer_country }}</span>
                        </div>
                        <div class="col-sm-4">
                            <div class="tcenter total-aud">
                                <p class="mb0 f16p">交易金额总计: </p>
                                <p class="f20p"><b>≈ {{ currencyFormat('AUD', $recipient->totalTransfer(), TRUE) }}</b></p>
                            </div>
                        </div>
                    </div>
                    <hr class="mt0">
                    <div>
                        <div>
                            <address>{{ sprintf('%s %s %s %s', $recipient->address, $recipient->address_2, $recipient->address_3, $recipient->postcode) }}</address>
                            <p>{{ sprintf('%s +%s %s %s', $recipient->phone_type, $recipient->phone_code, "*** ***",substr($recipient->phone, -4)) }}</p>                                
                            <p>{{ $recipient->email }}</p>
                            <hr>
                        </div>
                        <div>
                            <span class="help-block">银行信息</span>
                        @foreach ($banks as $bank)
                            <div class="recipient-bank-panel row">
                                <div class="col-sm-6">
                                    <p><strong>{{ sprintf('%s - %s', $bank->bank_name, $bank->branch_name) }}</strong> {{ $bank->swift_code ? sprintf('<span class="color-737">(SWIFT：%s)</span>', $bank->swift_code) : '' }}</p>
                                    <p><span class="color-737 mr10">{{ $bank->account_bsb ? 'BSB & ' : '' }}银行账号</span>{{ sprintf('%s %s', $bank->account_bsb, $bank->account_number) }}</p>
                                    <p>
                                        @foreach (explode(',', $bank->currency) as $c)
                                        <span class="mr10">{{ currencyFlag($c) . $c}}</span>
                                        @endforeach
                                    </p>
                                </div>
                                <div class="col-sm-3 bank-action-panel">
                                    <a data-toggle="modal" href="/recipient/edit-bank/{{ $bank->id }}" data-target="#myModal" data-backdrop="static"><span class="fa fa-pencil-square-o"></span> 编辑</a>
                                    <a href="/recipient/del-bank/{{ $bank->id }}" class="ml10 del-btn"><span class="fa fa-trash-o"></span> 删除</a>                                    
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    <div class="mt10"><a data-toggle="modal" href="/recipient/add-bank/{{ $recipient->id }}" data-target="#myModal" data-backdrop="static">+ 添加银行账号</a></div>
                </div>    
            </div>

            <div class="mt40"> 
                <h2><i class="fa fa-th-list"></i>  交易历史</h2>
                <hr>
                <div class="pannel-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>日期</th>
                                <th>流水号</th>
                                <th>银行账号</th>
                                <th>状态</th>
                                <th>金额</th>
                            </tr>
                        </thead>   
                        <tbody>
                        @foreach ($trans as $tran)
                            <tr onclick="window.open('{{ url('receipt', array($tran->receipt_id)) }}')" style="CURSOR:pointer">
                                <td>{{ date_word($tran->created_at) }}</td>
                                <td>{{ $tran->receipt_id }}</td>
                                <td>{{ $tran->account_number }}</td>
                                <td>
                                    @if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                                        <span class="label label-danger">{{ sprintf('<i class="fa fa-exclamation-triangle"></i> %s', overdueCheck($tran->created_at)) }}</span>
                                    @else
                                        <span class="label label-{{ receiptStatusStyle($tran->receipt->status) }}">{{ $tran->receipt->status }}</span>
                                    @endif
                                </td>
                                <td>{{ currencyFormat($tran->currency, $tran->amount, TRUE) }}</td>                                
                            </tr>
                        @endforeach
                        </tbody>  
                        <tfoot>
                            <tr>
                                @if (!empty($trans))
                                <td colspan="5">{{ $trans->links() }}</td>
                                @endif
                            <tr>
                        </tfoot>                                             
                    </table>
                </div>                
            </div>
        </div>
    </div> 

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>       
@stop

@section('inline-css')
{{ HTML::style('assets/css/typeahead.js-bootstrap.css') }}
@stop

@section('inline-js')
<script>
    $(document).ajaxSend(function(event, request, settings) {
        $.fancybox.showLoading();

    });
    $(document).ajaxComplete(function(event, request, settings) {
        $.fancybox.hideLoading();
    });

    $('.del-btn').click(function(e){
        var obj = $(this);
        bootbox.confirm("Are you sure you want to delete?", function(result) {
            if (result) {
                window.location.href = obj.attr('href');
            }
        }); 
        return false;
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        $(this).removeData('bs.modal');        
    })      

</script>
@stop
