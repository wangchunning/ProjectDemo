/*
 * contents list
 *
 *              , 2013-12-18
 */

$.extend( $.fn.dataTableExt.oStdClasses, {
    "sSortAsc"  : "th_sorting_asc",     // css for sorting asc
    "sSortDesc" : "th_sorting_desc",    // css for sorting desc
    "sSortable" : "th_sorting"          // css for normal th
} );


/**
 * change default sorting method
 */
jQuery.fn.dataTableExt.oSort['date-word-asc'] = date_word_asc;
jQuery.fn.dataTableExt.oSort['date-word-desc'] = date_word_desc;

$(function() {  

    // 记录table td 样式
    var f_Row = $(".contents-list tbody tr:first-child");
    var td_columns = new Array();
    if (f_Row.find('td').length > 1) {
        f_Row.find('td').each(function(){
            var sClass = $(this).attr('class');
            
            if (typeof(sClass) != 'undefined') {
                var td = { 'sClass' : sClass };
                $(this).attr('class', '');
            }else{
                var td = { 'sClass' : null };
            };
            td_columns.push(td);
        });
    };   

    /*
     * init datatables
     */
    $(".contents-list").dataTable({

        "bFilter"       : false,    // no searching
        "bPaginate"     : false,    // no paginating
        "bLengthChange" : false,    // no entries selection
        "bInfo"         : false,    // no foot information

        "aaSorting"     : [[0, 'desc']], // by default, sorting by first column

        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [] },  
            { 'sType' : 'date-word', 'aTargets': [ 0 ] }    // custom sorting for date_word
        ],

        "oLanguage"     : {
            "sEmptyTable"   : '<div class="text-center">-- No Records to Display --</div>'
        },
       "aoColumns": td_columns
    });

}); // function


/*
 * show more tx history
 */
var _page_idx = 2;  // page number index
var _current_records = 50; // init page records

$("#btn_show_more_tx").click(function() {

    // make url
    var _request_url = $(this).find('#request_url').val();
    var _request_param = $(this).find('#request_param').val();
    var _url_data = _request_url + '/' + _page_idx + '?' + _request_param;             

    var _url = encodeURI(_url_data);
    var _table_id = '.contents-list';
    
    /*
     * ajax getting tx data
     */
    $.getJSON(_url, null, function(json) {

        _table = $(_table_id).dataTable();

        _oSettings = _table.fnSettings();
 
        // send data to table
        _record_count = json.aaData.length;
        _current_records += _record_count;

        _total_record_count = json.total_count;
        for (var __idx = 0; __idx < _record_count; __idx++) {

              _table.oApi._fnAddData(_oSettings, json.aaData[__idx]);

        }
 
        // set table style
        _oSettings.aiDisplay = _oSettings.aiDisplayMaster.slice();

        _table.fnDraw();    // redraw table

        $("#btn_show_more_tx_content").text('1 - '+ _current_records +' record(s), total '+ _total_record_count +' record(s)');
        // show more button? 
        if (_current_records >= _total_record_count)
        {
            $("#btn_show_more_tx").remove();
        }

    }); // getJson

    _page_idx++;

}); // click
