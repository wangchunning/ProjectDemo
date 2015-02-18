/*
 * my recent activity in my account
 *
 *				, 2013-12-18
 */

$.extend( $.fn.dataTableExt.oStdClasses, {
    "sSortAsc"	: "th_sorting_asc",		// css for sorting asc
    "sSortDesc"	: "th_sorting_desc",	// css for sorting desc
    "sSortable"	: "th_sorting",			// css for normal th
} );

/**
 * change default sorting method
 */
jQuery.fn.dataTableExt.oSort['date-word-asc'] = date_word_asc;
jQuery.fn.dataTableExt.oSort['date-word-desc'] = date_word_desc;

$(function() {

	/*
	 * init datatables
	 */
	$("#recent_activity_table").dataTable({

		"bFilter"	    : false,	// no searching
		"bPaginate"		: false,	// no paginating
        "bLengthChange" : false,	// no entries selection
        "bInfo"         : false,	// no foot information

        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 2, 3 ] },   // column 3, 4 is unsortable
            { 'sType' : 'date-word', 'aTargets': [ 0 ] },    // custom sorting for date_word
        ],

        "oLanguage"		: {
        	"sEmptyTable"	: '<div class="text-center">-- 没有记录 --</div>',
    	},

        "aaSorting"     : [[0, 'desc']]	// by default, sorting by first column

	});

});


