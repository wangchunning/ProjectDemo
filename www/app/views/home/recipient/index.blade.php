@section('title')
我的收款人
@stop

@section('content')
    @include('home.subnav')
    @include('breadcrumb')
    <div class="container">

    	<div class="white-box-body all-radius">
            <div class="flat-panel">
                <!-- title -->
                <h2><i class="fa fa-users"></i>我的收款人 
                <span class="btn btn-sm btn-default f16p pull-right">
                		<a href="/recipient/add"><i class="fa fa-plus"></i> 添加收款人</a>
                </span>
                </h2>
          </div>

            <hr class="mt20">

            <div id="recipients-list-pannel">
                <div class="row">
                    <div class="col-sm-4 mb10">
                        <input id="list-search" type="search" name="search" placeholder="搜索收款人姓名" class="form-control">
                    </div>
                </div>
                <!-- recipients table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped mt10 search-list" id="my_recipients_table">
                        <thead>
                            <tr>
                                <th>姓名</th>
                                <th></th>
                                <th>联系电话</th>
                                <th>交易金额总计</th>
                                <!--<th></th>-->
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($recipients as $recipient)
                            <tr onclick="window.open('{{ url('recipient/detail', array($recipient->id)) }}', '_self')" style="CURSOR:pointer">
                                <td>{{ $recipient->account_name }}</td>
                                <td>
                                @foreach (explode(',', $recipient->currency) as $currency)
                                    {{ $currency }}
                                @endforeach
                                </td>
                                <td>{{ sprintf("%s %s", "*** ***", substr($recipient->phone_number, -4)) }}</td>
                                <td>≈ {{ currencyFormat('AUD', $recipient->totalTransfer(), TRUE) }}</td>
                                <!--<td>
                                    <a href="{{ URL::to('recipient/edit/' . $recipient->id) }}"><span class="fa fa-pencil-square-o"></span> Edit</a>
                                    <a href="{{ URL::to('recipient/del/' . $recipient->id) }}" class="del-btn ml10"><span class="fa fa-trash-o"></span> Delete</a>
                                </td>-->
                            </tr>
                        @endforeach
                        </tbody>                      
                    </table>
                </div>      
                <!--// table -->
            </div>
        </div>
    </div>    
@stop

@section('inline-js')
{{ HTML::script('assets/datatable/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/lib/my-recipients.js') }}
<script>
$(function() {

    $.extend( $.fn.dataTableExt.oStdClasses, { 
        /* css for pagination */
        "sPaging"       : "pagination ",        
        /* css for elements of pagination, such as <<,< 1,2,3,>,>> */
        "sPageButton"   : "page-button ",       
        /* active element of pagination - current page */
        "sPageButtonActive"     : "page-button page-button-active ",        
        /* disabled elements, such as last page */
        "sPageButtonStaticDisabled" : "page-button page-button-disabled "   
    });

    // 初始表格
    $(".search-list").dataTable({

        "bSort"             : false,    // no sorting
        "bFilter"           : true,     // no searching
        "bLengthChange"     : false,    // no entries selection
        "bInfo"             : false,    // no foot information
        "bPaginate"         : true,     // paginating        
        "iDisplayLength"    : 30,       // 每页显示行数
        "sPaginationType"   : "full_numbers",   // 分页样式 - 全数字

        "sDom"  : 'frti<"text-center"p>',       // 定义布局 - 分页居中

        "aoColumnDefs": [
            { 'bSearchable': false, 'aTargets': [ 1 ] }   // only first 3 columns can be searched
        ],

        /* 定义显示文字 */
        "oLanguage"     : {
            "sEmptyTable"   : '<div class="text-center">-- 没有记录 --</div>',
            "sZeroRecords"  : '<div class="text-center">-- 没有记录 --</div>',
            "oPaginate": {
                "sFirst"    : "<<",
                "sPrevious" : "<",
                "sNext"     : ">",
                "sLast"     : ">>"
            }
        }
    });  

    // 隐藏默认的搜索框
    $(".dataTables_filter").hide();

    // bind 搜索框
    $("#list-search").bind('keyup', function(e){
        $(".search-list").dataTable().fnFilter($(this).val());
    }); 

    // 删除操作提示
    $('.del-btn').click(function(e){
        var obj = $(this);
        bootbox.confirm("确定要删除么？", function(result) {
            if (result) {
                window.location.href = obj.attr('href');
            }
        }); 
        return false;
    })         
})
</script>
@stop


