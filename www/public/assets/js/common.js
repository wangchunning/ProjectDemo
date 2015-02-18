$(function() {
	$('.custom-tooltips').tooltip({
		placement: 'bottom',
		html: true
	});
	//$('.custom-tooltips').tooltip('show');
    goBackTop();

    /* 鼠标移上显示操作 */
    $('table').delegate('tr', 'mouseout', function(){
        $(this).find('.actions').hide();
    })
    .delegate('tr', 'mouseover', function(){
        $(this).find('.actions').show();
    });
    
    /* 关闭下拉菜单事件，变为鼠标滑动 */
    $('.navbar').off('click.bs.dropdown.data-api');
    var $dropdownLi = $('nav li.dropdown');

	$dropdownLi.mouseover(function() {
		$(this).addClass('open');
	}).mouseout(function() {
		$(this).removeClass('open');
	});
});

/**
 * 格式化当前显示的货币，只显示简称
 *
 * @return void
 */
function updateTip() {
    var from = $('#currency_have').find('option:selected').text();
    var to = $('#currency_want').find('option:selected').text();

    from = from.split('-');
    to = to.split('-');

    $('#currency_haveSelectBoxItText').text(from[0]);
    $('#currency_wantSelectBoxItText').text(to[0]);
    $('#lb_currency_have').html(from[1]);
    $('#lb_currency_to').html(to[1]);    
}

/* 返回顶部 */
function goBackTop() {
    var get_scroll = $(window).scrollTop();
    var window_h = $(window).height();

    var go_top = $('#free_gotop');
    if (get_scroll < window_h * 1.5) {
        go_top.hide();
    }
    $(window).scroll(function() {
        var get_scroll = $(this).scrollTop();
        if (get_scroll > window_h * 1.5) {
            go_top.fadeIn();
        } else {
            go_top.fadeOut();
        }
    })
    go_top.click(function(e) {
        $("html,body").animate({scrollTop: $('.container').offset().top}, 500);
        e.preventDefault();
    })
}

/**
 * sorting priority
 * @type {Array}
 */
var date_word_priority    = new Array();
date_word_priority['jus'] = 9;     // just_now
date_word_priority['sec'] = 8;     // second
date_word_priority['min'] = 7;     // minute
date_word_priority['hou'] = 6;     // hour
date_word_priority['day'] = 5;     // day

/**
 * date word sorting asc. comapre a and b
 * @param  {[string]} a [description]
 * @param  {[string]} b [description]
 * @return 
 *         1    : greater 
 *         -1   : smaller
 *         0    : equal
 */
function date_word_asc (a, b) {

    var _words_a = a.split(' ');
    var _words_b = b.split(' ');

    if (_words_a == 'just_now') {
        return 1;
    }
    if (_words_b == 'just_now') {
        return -1;
    }

    var _a_com_idx = a.indexOf(':');
    var _b_com_idx = b.indexOf(':');

    // if a , b are date, 16:27:19 17 Dec 2013
    if (_a_com_idx == _b_com_idx && _a_com_idx != -1) {
        
        return Date.parse(a) - Date.parse(b) > 0 ? 1 : -1;
    } 
    else if (_a_com_idx != -1 && _b_com_idx == -1) {
        return -1;
    }
    else if (_a_com_idx == -1 && _b_com_idx != -1) {
        return 1;
    }

    var _word_tag_a = _words_a[1].substring(0, 3);
    var _word_tag_b = _words_b[1].substring(0, 3);

    if (date_word_priority[_word_tag_a] != date_word_priority[_word_tag_b]) {
        return (date_word_priority[_word_tag_a] < date_word_priority[_word_tag_b]) ? -1 :
                (date_word_priority[_word_tag_a] > date_word_priority[_word_tag_b] ? 1 : 0);
    }

    return (parseInt(_words_a[0]) > parseInt(_words_b[0]) ? -1 : 
                (parseInt(_words_a[0]) < parseInt(_words_b[0]) ? 1 : 0));
};

/**
 * date word sorting desc. comapre a and b
 * @param  {[string]} a [description]
 * @param  {[string]} b [description]
 * @return 
 *         1    : smaller 
 *         -1   : greater
 *         0    : equal
 */
function date_word_desc (a, b) {

    var _words_a = a.split(' ');
    var _words_b = b.split(' ');

    if (_words_a == 'just_now') {
        return -1;
    }
    if (_words_b == 'just_now') {
        return 1;
    }

    var _a_com_idx = a.indexOf(':');
    var _b_com_idx = b.indexOf(':');

    // if a , b are date, 16:27:19 17 Dec 2013
    if (_a_com_idx == _b_com_idx && _a_com_idx != -1) {
        
        return Date.parse(a) - Date.parse(b) > 0 ? -1 : 1;
    } 
    else if (_a_com_idx != -1 && _b_com_idx == -1) {
        return 1;
    }
    else if (_a_com_idx == -1 && _b_com_idx != -1) {
        return -1;
    }

    var _word_tag_a = _words_a[1].substring(0, 3);
    var _word_tag_b = _words_b[1].substring(0, 3);

    if (date_word_priority[_word_tag_a] != date_word_priority[_word_tag_b]) {
        return (date_word_priority[_word_tag_a] < date_word_priority[_word_tag_b]) ? 1 :
                (date_word_priority[_word_tag_a] > date_word_priority[_word_tag_b] ? -1 : 0);
    }

    return (parseInt(_words_a[0]) > parseInt(_words_b[0]) ? 1 : 
                (parseInt(_words_a[0]) < parseInt(_words_b[0]) ? -1 : 0));
};

/**
 * 从 table 中读取数据，并以 JSON 方式 post 到 server
 * 数据格式 {“title”:[...], "data":[[...], [...], ...]}
 * 
 * @param  {[type]} url      远程 url
 * @param  {[type]} table_id 页面 table 的id id
 * @param  {[type]} row_from 开始行号，遵循 JQuery 选择器原则
 * @param  {[type]} row_to   终止行号，遵循 JQuery 选择器原则
 * @return {[type]}          
 */
function export_table (url, table_id, row_from, row_to) {

    /**
     *  title - th
     */
    _t_data = new Array();
    $("#"+table_id).find("th").each(function(){

        _t_data.push($.trim($(this).text()));
    });   

    /**
     * data - tr, td
     */
    _d_data = new Array();
    $("#"+table_id).find("tr").slice(row_from, row_to).each(function(){
        var _r_data = [];
        $(this).find("td").each(function(){

            _r_data.push($.trim($(this).text()));
        });
        _d_data.push(_r_data);
    });        

    //var json_str = JSON.stringify(_t_data);  //[{"id":1,"name":"test1","age":2}]
    //alert(jsonString);   

    /**
     * post json
     */
    _form = $("<form></form>");
    _form.attr('action', url);
    _form.attr('method','post');
    _in_title = $("<input type='hidden' name='title'/>");
    _in_title.attr('value', JSON.stringify(_t_data));
    _in_data = $("<input type='hidden' name='data'/>");
    _in_data.attr('value', JSON.stringify(_d_data));
    _form.append(_in_title);
    _form.append(_in_data);

    _form.submit();
}

/**
 * 将字符首字母转为大写
 *
 * @param  string
 * @return string
 */
function upperFirstLetter(str) {
    if ( ! str ) {
        return;
    }   
    return str.replace(/\b\w+\b/g, function(word) {   
        return word.substring(0,1).toUpperCase( ) +  word.substring(1);   
    });   
}

// 格式化金额
function number_format(number, decimals, dec_point, thousands_sep) {
  //  discuss at: http://phpjs.org/functions/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56);
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ');
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '');
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.');
  //   returns 4: '67,00'
  //   example 5: number_format(1000);
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2);
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1);
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.');
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0);
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2);
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4);
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3);
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ');
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '');
  //  returns 14: '0.00000001'

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}






/**
 * 上传 file
 */
function common_file_upload(url, btn_id, btn_container, result_para, result_container)
{
    var uploader = new plupload.Uploader({
        runtimes : 'gears,html5,flash,silverlight,browserplus',     
        max_file_size : '5mb',
        container : btn_container,
        browse_button : btn_id,
        url : url,
        flash_swf_url : '/assets/plupload/plupload.flash.swf',
        silverlight_xap_url : '/assets/plupload/plupload.silverlight.xap',        
        filters: {
          mime_types : [
            { title : "Image files", extensions : "jpg,gif,png,jpeg" },
            { title : "Doc files", extensions : "pdf,doc,docx" }
          ]
        },
     // Views to activate
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        }

        //resize : {width : 600, height : 450, quality : 100}  
    });

    uploader.init();

    // 选择上传文件
    uploader.bind('FilesAdded', function(up, files){
        $.fancybox.showLoading();
        up.start();     
    });
    // 上传前
    uploader.bind('BeforeUpload', function(up, file) {
        up.settings.url =  up.settings.url + "?plupload_id=" + file.id;     
    }); 
    // 上传进度
    uploader.bind('UploadProgress', function(up, file){

    });
    // 上传错误
    uploader.bind('Error', function(up, error){
        alert(error.message);
    });
    // 上传完成
    uploader.bind('FileUploaded', function(up, file, rep){
        $.fancybox.hideLoading();
        var rs = $.parseJSON(rep.response);

        if (rs.status == 'error')
        {
            alert(rs.data.msg);
            return;
        }

        //$('#'+result_container).empty();
        $('#'+result_container).append(
            '<input type="hidden" name="'+ result_para +'" value="' + rs.data.filePath + '">'
        );

        $('#'+result_container).append(file.name  + ' (' + plupload.formatSize(file.size) + ') 已上传');

    })
}
/**
 * 上传 file
 */
function multi_file_upload(url, container, result_para, result_container)
{
	var uploader = $(container).pluploadQueue({
        runtimes : 'html5,flash,silverlight,html4',
        url : url,
        max_file_size : '1.5mb',
        chunk_size: '1mb',
        filters: {
            mime_types : [
              { title : "Image files", extensions : "jpg,gif,png,jpeg" },
              { title : "Doc files", extensions : "pdf,doc,docx" }
            ]
          },

        flash_swf_url : '/plupload/js/Moxie.swf',
        silverlight_xap_url : '/plupload/js/Moxie.xap',
        init : {
        	Error: function(up, error) {
        		alert(error.message);
            },
        	FileUploaded: function(up, file, rep) {

                var rs = $.parseJSON(rep.response);

                if (rs.status == 'error')
                {
                    alert(rs.data.msg);
                    return;
                }


                $(result_container).append(
                    '<input type="hidden" name="'+ result_para +'" value="' + rs.data.filePath + '">'
                );
            }
        }
    });

}


function openPostWindow(url,args,name){  
    
    //创建表单对象  
    var _form = $("<form></form>",{  
        'id':'tempForm',  
        'method':'post',  
        'action':url,  
        'target':name,  
        'style':'display:none'  
    }).appendTo($("body"));  
      
    //将隐藏域加入表单  
    for(var i in args){  
        _form.append($("<input>",{'type':'hidden','name':i,'value':args[i]}));  
    }  
   
      
    //绑定提交触发事件  
    _form.bind('submit',function(){  
        window.open("about:blank",name);  
    });  
      
    //触发提交事件  
    _form.trigger("submit");  
    //表单删除  
    _form.remove();   
}  

