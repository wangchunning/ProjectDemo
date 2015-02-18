/*
 * my tranactions history
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

    /* 
     * date picker 
     */
    $( ".datepicker" ).datepicker();    

    /*
     * init datatables
     */
    $("#my_tx_history_table").dataTable({

        "bFilter"       : false,    // no searching
        "bPaginate"     : false,    // no paginating
        "bLengthChange" : false,    // no entries selection
        "bInfo"         : false,    // no foot information

        "aaSorting"     : [[0, 'desc']], // by default, sorting by first column

        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 2, 3 ] },   // column 3, 4 is unsortable
            { 'sType' : 'date-word', 'aTargets': [ 0 ] },    // custom sorting for date_word
        ],

        "oLanguage"     : {
            "sEmptyTable"   : '<div class="text-center">-- 没有记录 --</div>',
        }

    });

}); // function

$('#tx_search').click(function() {

    var url = $('form.date-pick').attr('action');
    window.location.href = url + '&date_f=' + $('#date_f').val() + 
        					'&date_t=' + $('#date_t').val();

});

/*
 * show more tx history
 */
var _page_idx = 2;  // page number index

$("#btn_show_more_tx").click(function() {

	var _word2date = new Array();
	_word2date['Today'] = 'today';
	_word2date['This Week'] = 'thisWeek';
	_word2date['Last Week'] = 'lastWeek';
	_word2date['This Month'] = 'thisMonth';
	_word2date['Last Month'] = 'lastMonth';
	_word2date['Last 3 Month'] = 'last3Month';

    /*
     * para. 
     */
    var _status = $.trim($('#status_button').text());           // status
    var _date_word = $.trim($('#date_word_button').text());     // date word
    var _date_f = $.trim($('#date_f').val());                   // start date
    var _date_t = $.trim($('#date_t').val());                   // end date

    // make url
    var _url_data = '/my-tx-history/json/' + _page_idx + '?';

    if (_status != '全部状态') {

    	_url_data += 'cur_status=' + _status;
    } 

    if (_date_word != '全部日期') {

    	_url_data += '&dw=' + _word2date[_date_word];
    }
    
    if (_date_f.length != 0)
    {
    	_url_data += '&date_f=' + _date_f;
    }  
    if (_date_t.length != 0)
    {
    	_url_data += '&date_t=' + _date_t;
    }                

    var _url = encodeURI(_url_data);
    var _table_id = '#my_tx_history_table';
    $.fancybox.showLoading();
    /*
     * ajax getting tx data
     */
    $.getJSON(_url, null, function(json) {

        

        _table = $(_table_id).dataTable();

        _oSettings = _table.fnSettings();
     
        _table.fnClearTable(this);
 
        // send data to table
        _record_count = json.aaData.length;
        _total_record_count = json.total_count;
        for (var __idx = 0; __idx < _record_count; __idx++) {
              _table.oApi._fnAddData(_oSettings, json.aaData[__idx]);
        }
 
        // set table style
        _oSettings.aiDisplay = _oSettings.aiDisplayMaster.slice();

        _table.fnDraw();    // redraw table

        $("#btn_show_more_tx_content").text('1 - '+ _record_count +' 条记录, 总计 '+ _total_record_count +' 条记录');
        $("#in_amount").text(json.inout.in.toFixed(2) + ' AUD');
        $("#out_amount").text(json.inout.out.toFixed(2) + ' AUD');
        // show more button? 
        if (_record_count == _total_record_count)
        {
            $("#btn_show_more_tx").remove();
        }
        $.fancybox.hideLoading();
    }); // getJson

    _page_idx++;
    
}); // click
