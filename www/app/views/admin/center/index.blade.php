@section('title')
我的工作台 - 天添财富后台管理系统
@stop

@section('content')
@include('admin.center.subnav')
<div class="container flat-panel">
    <div class="all-radius white-box-body">
        <h4 class="mt40">企业融资申请</h4>
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
                    @if (empty($business_finance))
                    <tr><td colspan="7" align="center">- 没有记录 -</td></tr>
                    @else
                        @foreach ($business_finance as $item)
                        <tr onclick="window.location.href=('{{ url('/admin/financing/business-financing-detail', $item->id) }}')" style="CURSOR:pointer">
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
  

        <hr>
        <h4 class="mt40">债权转让</h4>
        <ul class="nav nav-tabs" id="nav-vip">
            <li class="active"><a href="#non_performing_asset" data-toggle="pill">不良资产</a></li>
            <li><a href="#gov_asset" data-toggle="pill">金融国资</a></li>
            <li><a href="#credit_asset" data-toggle="pill">信贷资产</a></li>
        </ul>
        <div class="tab-content table-responsive">  
            <div class="tab-pane active" id="non_performing_asset">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mt20">
                        <thead>
                            <tr>
                                <th>项目名称</th>
                                <th>挂牌价格</th> 
                                <th>债权本金</th>
                                <th>所属行业</th>
                                <th>所属地区</th>
                                <th>截止日期</th>                           
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @if (empty($non_perform_asset))
                            <tr><td colspan="7" align="center">- 没有记录 -</td></tr>
                            @else
                                @foreach ($non_perform_asset as $item)
                                <tr onclick="window.location.href=('{{ url('/admin/debt/non-performing-asset-detail', $item->id) }}')" style="CURSOR:pointer">
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->expect_price }}</td>
                                    <td>{{ $item->debt_principal }}</td>
                                    <td>{{ $item->industry }}</td>
                                    <td>{{ $item->area }}</td>
                                    <td>{{ $item->expire_date }}</td>
                                    <td>{{ $item->status_desc }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane" id="gov_asset">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mt20">
                        <thead>
                            <tr>
                                <th>项目名称</th>
                                <th>所属行业</th> 
                                <th>地区</th>
                                <th>转让价格</th>   
                                <th>挂牌起始日期</th>
                                <th>挂牌终止日期</th>                            
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @if (empty($gov_asset))
                            <tr><td colspan="7" align="center">- 没有记录 -</td></tr>
                            @else                        
                                @foreach ($gov_asset as $item)
                                <tr onclick="window.location.href=('{{ url('/admin/debt/gov-asset-detail', $item->id) }}')" style="CURSOR:pointer">
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->industry }}</td>
                                    <td>{{ $item->area }}</td>
                                    <td>{{ $item->expect_price }}</td>
                                    <td>{{ $item->start_date }}</td>
                                    <td>{{ $item->expire_date }}</td>
                                    <td>{{ $item->status_desc }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane" id="credit_asset">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mt20">
                        <thead>
                            <tr>
                                <th>项目名称</th>
                                <th>贷款行所在地</th> 
                                <th>转让方式</th>
                                <th>转让本金</th>  
                                <th>转让起止日</th>  
                                <th>转让利率</th>  
                                <th>管理费</th>  
                                <th>借款人所在地</th>  
                                <th>借款人行业</th>  
                                <th>借款方式</th>                            
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @if (empty($credit_asset))
                            <tr><td colspan="11" align="center">- 没有记录 -</td></tr>
                            @else  
                                @foreach ($credit_asset as $item)
                                <tr onclick="window.location.href=('{{ url('/admin/debt/credit-asset-detail', $item->id) }}')" style="CURSOR:pointer">
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->loan_bank_area }}</td>
                                    <td>{{ $item->transfer_model }}</td>
                                    <td>{{ $item->transfer_principal }}</td>
                                    <td>{{ $item->start_date.'-'.$item->expire_date }}</td>
                                    <td>{{ $item->transfer_rate }}</td>
                                    <td>{{ $item->admin_charge }}</td>
                                    <td>{{ $item->borrower_area }}</td>
                                    <td>{{ $item->borrower_industry }}</td>
                                    <td>{{ $item->borrow_model }}</td>
                                    <td>{{ $item->status_desc }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
@stop

@section('inline-js')

@stop