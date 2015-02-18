$(function(){

    // 点击添加 note
    $('.add-note').on('click', function(){
        $('#new-note').show();          
    });

    // 点击 cancel 按钮
    $('#note-panel').delegate('a.cancel-btn', 'click', function(){
        var obj = $(this).parents('.desc-input');
        obj.hide();
        obj.prev('p').show();         
    });

    // 点击修改按钮
    $('#note-panel').delegate('a.desc-edit', 'click', function(){
        var obj = $(this).parents('.desc');
        obj.find('.desc-show').hide();
        obj.find('.desc-input').show();        
    });

    // 点击新增保存按钮
    $('#new-note .save-desc-btn').on('click', function(){
        var uid = $('#note-panel').attr('data-uid');
        var item_id = $('#note-panel').attr('data-item');
        var type = $('#note-panel').attr('data-type');
        var desc = $('#new-note .tran-desc').val();

        $.ajax({
            url : '/admin/note/add',
            type : 'POST',
            dataType : 'json',
            data : {uid : uid, item_id : item_id, type : type, desc : desc},
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    $('#form-error-tip').html(rep.data.msg);
                    return false;
                }

                var html = '<div class="desc">' + rep.data.output;
                html += '<div><p class="desc-show">'+ rep.data.desc +'</p><div class="desc-input"><div><input type="text" class="tran-desc form-control" name="desc" value="'+ rep.data.desc +'"><a href="javascript:;" class="btn btn-primary save-desc-btn">Update</a> <a href="javascript:;" class="btn btn-default cancel-btn">Cancel</a></div></div></div></div>';

                $('#new-note').before(html);    

                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);

                $('#new-note .tran-desc').val('');
                $('#new-note').hide();          
            },
            beforeSend : function(){
                $.fancybox.showLoading();
                $('#form-error-tip').html('');
            }
        })         
    });
    
    // 点击修改保存按钮
    $('#note-panel').delegate('.desc a.save-desc-btn', 'click', function(){
        var obj = $(this).parents('.desc');
        var desc = obj.find('.tran-desc').val();
        var note_id = obj.find('.desc-edit').attr('data-id');
        $.ajax({
            url : '/admin/note/edit',
            type : 'POST',
            dataType : 'json',
            data : {note_id : note_id, desc : desc},
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    alert(rep.data.msg);
                    return false;
                }
                obj.find('.desc-show').html(desc).show();
                obj.find('.desc-input').hide();

                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })         
    });

    // 删除 note
    $('#note-panel').delegate('a.desc-delete', 'click', function(){
        var obj = $(this).parents('.desc');
        var note_id = obj.find('.desc-delete').attr('data-id');
        $.ajax({
            url : '/admin/note/remove',
            type : 'POST',
            data : {note_id : note_id},
            dataType : 'json',
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    alert(rep.data.msg);
                    return false;
                }
                obj.remove();

                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
            }
        })
    });  
})