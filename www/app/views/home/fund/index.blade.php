@section('title')
存款
@stop

@section('content')
    <div class="container">
        <div class="dashboard-body">
            <div class="flat-panel">
                <h2>存款</h2>
                <p class="tips">
                	通过存款，您可以将资金从银行账号转账到 Anying 账号
                </p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>选项</th>
                                <th>描述</th>
                                <th>处理时长</th>
                                <th>费用</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>银行转账</td>
                                <td><a href="/fund/add">从银行账号转账
</a></td>
                                <td>3-5 个工作日</td>
                                <td>免费</td>
                            </tr>
                            <tr>
                                <td>信用卡转账</td>
                                <td>从信用卡转账  <small class="color-999"><em>(暂不支持)</em></small>  </td>
                                <td>即时到账</td>
                                <td>2% 手续费</td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
@stop
