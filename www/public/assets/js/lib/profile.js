var profile = {
    init : function() {
        this.bindEvents();
    },

    bindEvents : function() {
        // 重新发送验证邮件
        $('.send-verify-email-btn').on('click', function(event){
        	
        	event.preventDefault();

            $.ajax({
                url : '/profile/send-verify-email',
                data: {email: $('#email').val()},
                type : 'POST',
                success : function(rep) {
                    $.fancybox.hideLoading();
                    $('#handingMsg').html('<div class="alert alert-success">' + rep.data.msg + '</div>');                    
                },
	            beforeSend : function() {
	                $.fancybox.showLoading();
	            }
            })
        })
    }
}

var security = {

    init : function() {
        this.bindEvent();
    },

    bindEvent : function() {
    	
        // 发送 PIN
        $('#get-pin-btn').on('click', function(event){
        	
        	event.preventDefault();
        	
        	var _mobile = $('#mobile').val();
        	security.countDown();
            $.fancybox.showLoading();
            
            $.ajax({
                url : '/profile/pin',
                type : 'POST',
                data : {mobile : _mobile},
                success : function(rep) {
                	$.fancybox.hideLoading();
                    var className = rep.status === 'ok' ? 'success' : 'danger';
                    $('#handingMsg').html('<div class="alert alert-' + className + '">' + rep.data.msg + '</div>');
                    if (rep.status === 'error') {
                        $('#pinField').addClass('hide');
                        return;
                    }
                }
            })
        })
    },

    countDown : function() {
        $('#pinField').removeClass('hide');
        $('#get-pin-btn').attr('disabled', 'disabled');
        var count = 20;
        var string = $('#get-pin-btn').html();  
        var id = setInterval(function() {
            if (count === 1) clearInterval(id);             
            count--;
            $('#get-pin-btn').html(string + '(' + count + ')');
            if (count === 0) $('#get-pin-btn').html(string).removeAttr('disabled');
        }, 1000);
    }
}

/**
* 上传 Photo id 图片
*
*/
function photoIdUpload()
{
    var uploader = new plupload.Uploader({
        runtimes : 'gears,html5,flash,silverlight,browserplus',     
        max_file_size : '5mb',
        container : 'photo-id-container',
        browse_button : 'photo-id-upload',
        url : '/upload',
        flash_swf_url : '/assets/plupload/plupload.flash.swf',
        silverlight_xap_url : '/assets/plupload/plupload.silverlight.xap',        
        filters : [{title : "Image files", extensions : "jpg,gif,png,jpeg"}]
        //resize : {width : 600, height : 450, quality : 100},         
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

        $('#photo-id-field').html(
            '<input type="hidden" name="photo_id_file" value="' + rs.data.filePath + '">'
            + '<a class="group" rel="group_photo" href="/' + rs.data.filePath + '"><img src="/' + rs.data.filePath + '" class="img-responsive"></a>'
            + '<a class="remove" href="javascript:;"><i class="fa fa-times remove"></i>Delete</a>'
        );
        $('#photo-id-name').html('');
    })
}

/**
* 上传 Proof of Addresss 图片
*
*/
function addrProofUpload()
{
    var uploader = new plupload.Uploader({
        runtimes : 'gears,html5,flash,silverlight,browserplus',     
        max_file_size : '5mb',
        container : 'addr-proof-container',
        browse_button : 'addr-proof-upload',
        url : '/upload',
        flash_swf_url : '/assets/plupload/plupload.flash.swf',
        silverlight_xap_url : '/assets/plupload/plupload.silverlight.xap',        
        filters : [{title : "Image files", extensions : "jpg,gif,png,jpeg"}],
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

        $('#addr-proof-field').html(
            '<input type="hidden" name="addr_proof_file" value="' + rs.data.filePath + '">'
            + '<a class="group" rel="group_addr_proof" href="/' + rs.data.filePath + '"><img src="/' + rs.data.filePath + '" class="img-responsive"></a>'
            + '<p><a class="remove" href="javascript:;"><i class="fa fa-times"></i>Delete</a></p>'
        );
        $('#addr-proof-name').html('');
    })
}