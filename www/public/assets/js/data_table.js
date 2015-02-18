
$.extend( $.fn.dataTableExt.oStdClasses, {
    "sSortAsc"  : "th_sorting_asc",     // css for sorting asc
    "sSortDesc" : "th_sorting_desc",    // css for sorting desc
    "sSortable" : "th_sorting"          // css for normal th
} );

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


function init_data_table(table_id, has_page, has_search, search_input_id) 
{  

    /*
     * init datatables
     */
    $('#' + table_id).dataTable({

        "bFilter"       : has_search,    // no searching
        "bPaginate"     : has_page,    // no paginating
        "bLengthChange" : false,    // no entries selection
        "bInfo"         : false,    // no foot information
        "iDisplayLength"    : 10,       // 每页显示行数
        "sPaginationType"   : "full_numbers",   // 分页样式 - 全数字

        "sDom"  : 'frti<"text-center"p>',       // 定义布局 - 分页居中

        "aaSorting"     : [[0, 'desc']], // by default, sorting by first column

        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 'unsortable' ] },   // 
            { 'bSearchable': false, 'aTargets': [ 'unsearchable' ] }
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
    
    if (has_search)
    {
        /* 隐藏默认的搜索框 */
        $(".dataTables_filter").hide();

        /* bind 搜索框 */
        $('#' + search_input_id).bind('keyup', function(e){
            $('#' + table_id).dataTable().fnFilter($(this).val());
        }); 
        
    }
         

} // function

