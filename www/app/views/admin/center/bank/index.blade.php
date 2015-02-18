@section('title')
Bank
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="dashboard-body">
            <h4>{{ $current_c }} Bank Accounts</h4>
            <div class="row">
                <ul class="col-sm-10 bank-filter list-inline">
                @foreach ($filters as $c)
                    <li{{ $c == $current_c ? ' class="current"' : ''; }}><a href="/admin/bank/index/{{$c}}">{{$c}} Banks</a></li>
                @endforeach
                </ul>
                <div class="col-sm-2">
                    @if (check_perm('add_bank', FALSE))
                    <span class="btn btn-default"><a href="/admin/bank/add">+ Add New Bank</a></span>
                    @endif
                </div>
            </div>
            <div id="bank-pannel">        
                <div class="pannel-body table-responsive">             
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th>Bank</th>
                                <th>Account Name</th>
                                <th>BSB</th>
                                <th>Account No.</th>
                                <th>Status</th>
                            @if ($current_c == 'CNY')
                                <th>Limit</th>
                            @endif
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ $banks->isEmpty() ? '<tr><td colspan="7" align="center">- No Records to Display -</td></tr>' : ''}}
                        @foreach ($banks as $bank)
                            <tr class="{{ $bank->id }}" style="CURSOR:pointer">
                                <td>
                                    @if (check_perm('delete_bank', FALSE))
                                    <a href="javascript:;" title="Remove" class="remove-btn fa fa-minus-circle"></a>
                                    @endif
                                </td>
                                <td><a href="/admin/bank/edit/{{$bank->id}}">{{ $bank->bank }}</a></td>
                                <td>{{ $bank->account }}</td>
                                <td>{{ $bank->BSB }}</td>
                                <td>{{ $bank->account_number }}</td>
                                <td>
                                @if (check_perm('edit_bank', FALSE))
                                    <input type="checkbox" class="switch-checkbox" id="bank_{{ $bank->id }}" data-on-color="success"  {{ $bank->status == 1 ? 'checked' :  ''; }}>
                                @else
                                    <?php echo $bank->status == 1 ? 'On' :  'Off'; ?>
                                @endif
                                </td>
                            @if ($current_c == 'CNY')
                                <td>{{ $bank->limit == 0 ? "Unlimited" : $bank->limit }}</td>
                            @endif
                                <td>
                                    <span>{{ currencyFormat($current_c, $bank->getAmount($current_c), true) }}</span>
                                    @if (check_perm('edit_bank_amount', FALSE))
                                        <a href="javascript:;" title="Edit Amount" class="editable fa fa-pencil"></a>
                                        <input type="hidden" class="amount" value="{{ number_format($bank->getAmount($current_c), 2) }}">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('inline-css')
{{ HTML::style('assets/css/bootstrap-switch.min.css') }}
@stop

@section('inline-js')
{{ HTML::script('/assets/js/bootstrap-switch.min.js') }}
<script>
    $(function(){
        var current_c = '{{ $current_c }}';
        // 移除某银行帐号
        $('.remove-btn').click(function(e){
            e.stopPropagation();
            if(confirm("确定要删除该银行帐户吗？"))
            {
                var obj = $(this);
                $.ajax({
                    url : '/admin/bank/remove',
                    type : 'POST',
                    dataType : 'json',
                    data : {
                        currency : obj.closest('tr').attr('class')
                    },
                    success : function(rep) {
                        obj.closest('tr').remove();
                        // 最后一个银行? 删除后跳转到银行管理首页
                        var count_bank = $('#bank-pannel table tbody tr').length;
                        if (count_bank == 0) {
                            window.location.href = '/admin/bank';
                        };                       
                    }
                })
            }        
        });

        // 点击跳转到银行账号流水页面
        $('#bank-pannel tbody tr[style="CURSOR:pointer"]').on('click', function(){
            var bank_id = $(this).attr('class');
            window.location.href = '/admin/bank/log/' + bank_id + '/' + current_c;
        });

        $(".switch-checkbox").bootstrapSwitch();
        // 消除该行的点击事件影响
        $('#bank-pannel table td').delegate('.bootstrap-switch', 'click', function(e){
            e.stopPropagation();
        });
        $(".switch-checkbox").on('switchChange.bootstrapSwitch', function (e, data) {
            e.stopPropagation();
            var obj = $(this),
            value = data;
            $.ajax({
                url : '/admin/bank/change-status',
                type : 'POST',
                dataType : 'json',
                data : {
                    id : obj.closest('tr').attr('class'),
                    status : value
                },
                success : function(rep) {
                    if (rep.status == 'error') {
                        alert(rep.data.msg);
                    };                      
                }
            })
        });

        /* 点击编辑bank balance操作 */
        $('#bank-pannel table td').delegate('.editable', 'click', function(e){
            e.stopPropagation();
            var obj = $(this);
            var bank_id = obj.closest('tr').attr('class');
            var objTD = obj.parent('td');
            var oldValue = obj.siblings('.amount').val();
            var input = $("<input type='text' class='form-control' value='" + oldValue + "' />"); 
            objTD.find('span').hide();
            obj.hide(); 
            objTD.append(input);
 
            // 换汇金额限制只能输入数字
            objTD.find('input[type="text"]').numerical({
                decimal : true
            });
            objTD.find('input[type="text"]').focus();

            // 文本框失焦后处理
            input.blur(function() {           
                var new_val = $(this).val();
                // 值并未改变
                if (new_val == oldValue) {
                    input.remove();
                    objTD.find('span').show();
                    obj.show();
                    return;
                };   
                $.ajax({
                    url : '/admin/bank/edit-amount/' + bank_id,
                    type : 'POST',
                    dataType : 'json',
                    data : { amount : new_val, currency : current_c },
                    success : function(rep) {
                        $.fancybox.hideLoading();
                        if (rep.status == 'error') {
                            alert(rep.data.msg);
                        } else {
                            obj.siblings('.amount').val(rep.data.amount);
                            objTD.find('span').text(rep.data.amount_format);
                        };
                        input.remove();
                        objTD.find('span').show();
                        obj.show();
                    },
                    beforeSend : function() {
                        $.fancybox.showLoading();
                    }
                })
            });
        });
    })
</script>
@stop