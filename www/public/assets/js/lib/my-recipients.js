/*
 * show more recipients
 */
var _page_idx = 2;  // page number index

$("#btn_show_more_recipients").click(function() {

    // make url
    var _url = '/recipient/json/' + _page_idx; 
    var _table_id = '#my_recipients_table';
    
    /*
     * ajax getting data
     */
    $.getJSON(_url, null, function(json) {

        var _table = $(_table_id);
 
        /*
         * clear table
         */
        $(_table_id + " tbody").empty(); // clear table

        /*
         * send data to table
         */ 
        var _record_count = json.aaData.length;
        var _total_record_count = json.total_count;         

        if (_record_count == 0) {
             $(_table_id + " tbody").append($('<tr><td colspan="4" align="center">- 没有记录 -</td></tr>'));
        }

        for (var __row_idx = 0; __row_idx < _record_count; __row_idx++) {

        	var __td = $("<td></td>"); 	
            var __row_data = json.aaData[__row_idx];

            __td.append('<td>' + __row_data.account_name + '</td>');
            __td.append('<td>' + __row_data.amount + '</td>');
            __td.append('<td>≈  ' + __row_data.total + '</td>');

            __td.append('<td>' +
	                        '<a href="/recipient/edit/' + __row_data.id + '" >' + 
	                            '<span class="fa fa-pencil-square-o"></span> Edit' + 
	                        '</a>' +
	                        '<a href="/recipient/del/' + __row_data.id + '" class="del-btn ml10">'+
	                            '<span class="fa fa-trash-o"></span> Delete'+
	                        '</a>' +
                        '</td>'
                );

            _table.append('<tr>' + __td.html() + '</tr>');            
        }

        /*
         * show more ?
         */
        $("#btn_show_more_recipients_content").text('1 - '+ _record_count +' 条记录, 总计 '+ _total_record_count +' 条记录');
        // show more button? 
        if (_record_count == _total_record_count)
        {
            $("#btn_show_more_recipients").remove();
        }

    }); // getJSON

    _page_idx++;

}); // click
