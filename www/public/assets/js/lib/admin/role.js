$(function(){

    // 点击新增/修改按钮
    $('.add-role,.role-list').delegate('a.role-set', 'click', function(){
        var obj = $(this);
        $('#form-error-tip').html('');
        if (obj.hasClass('add')) {
            $('#role-modal .modal-title').text('Add Role');
            $('#role-modal .modal-footer .btn-primary').text('Add');
            $('#role-modal #role_id').val('');
            $('#role-modal #name').val('');
            $('#role-modal #description').val('');
        } else {
            var p_tr = obj.parents('tr');
            $('#role-modal .modal-title').text('Edit ' + p_tr.find('td').eq(0).text());
            $('#role-modal .modal-footer .btn-primary').text('Save changes');
            $('#role-modal #role_id').val(obj.attr('data-id'));
            $('#role-modal #name').val(p_tr.find('td').eq(0).text());
            $('#role-modal #description').val(p_tr.find('td').eq(1).text());
        }           
    });

    // 保存 role 信息
    $('#role-modal #save-btn').click(function(){
        var role_id = $('#role-modal #role_id').val();
        var role_url,role_data;
        if (role_id != '') {
            role_url = '/admin/setting/role-edit';
            role_data = {
                role_id : role_id,
                name : $('#name').val(),
                description : $('#description').val()
            }
        }else{
            role_url = '/admin/setting/role-add';
            role_data = {
                name : $('#name').val(),
                description : $('#description').val()
            }
        }
        $.ajax({
            url : role_url,
            type : 'POST',
            dataType : 'json',
            data : role_data,
            success : function(rep) {
                if (rep.status == 'error'){
                    $.fancybox.hideLoading();
                    $('#form-error-tip').html(rep.data.msg);
                    return false;
                }
                if (rep.data.action == 'add') {
                    var $item = $("<tr>").addClass('role-'+rep.data.role_id);
                    var $td1 = $("<td>").html('<a href="/admin/setting/role-permission/'+ rep.data.role_id +'">'+ $('#name').val() +'</a>');
                    $item.append($td1);
                    var $td2 = $("<td>").text($('#description').val());
                    $item.append($td2);
                    var td3 = '<div class="btn-group actions"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down f14p"></i></button>';
                    td3 += '<ul class="dropdown-menu"><li><a class="role-set" data-toggle="modal" data-id="'+ rep.data.role_id +'" href="#role-modal">Edit</a></li><li><a class="role-remove" href="/admin/setting/role-remove/'+ rep.data.role_id +'">Remove</a></li></ul></div>';
                    var $td3 = $("<td class=\"action-block\">").html(td3);
                    $item.append($td3);
                    $item.appendTo('.role-list tbody');
                }else{
                    var p_tr = $('.role-'+rep.data.role_id);
                    p_tr.find('td').eq(0).html('<a href="/admin/setting/role-permission/'+ rep.data.role_id +'">'+ $('#name').val() +'</a>');
                    p_tr.find('td').eq(1).text($('#description').val());
                };

                $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                $('#role-modal').modal('hide');
                setTimeout('$.fancybox.hideLoading()', 1500);
            },
            beforeSend : function(){
                $.fancybox.showLoading();
                $('#form-error-tip').html('');
            }
        })
    });

    // 删除角色
    $('.role-list').delegate('a.role-remove', 'click', function(){
        var obj = $(this);
        var uri = obj.attr('href');
        bootbox.confirm("Are you sure you want to delete?", function(result) {
            if (result) {
                $.ajax({
                    url : uri,
                    type : 'GET',
                    dataType : 'json',
                    success : function(rep){
                        if (rep.status == 'error'){
                            $.fancybox.hideLoading();
                            alert(rep.data.msg);
                            return false;
                        }
                        $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:38px;font-size:25px;" class="fa fa-check"></i>');
                        obj.parents('tr').remove();
                        setTimeout('$.fancybox.hideLoading()', 1500);
                    },
                    beforeSend : function(){
                        $.fancybox.showLoading();
                    }
                });   
            }    
        })      
        return false;
    });

    // 自动选择依赖权限
    $('.perm-required input[type="checkbox"]').click(function(){
        if ($(this).is(':checked')) {
            var par = $(this).parents('.perm-required');
            par.find('input').eq(0).prop('checked', true);
        };
    }); 

    // 全选
    $('.sel_all').bind('change', function (event) {
            var checkboxes = $(this).parents('div.module').find('input:checkbox');
            if($(this).is(':checked')) {
                checkboxes.prop('checked', 'checked');
            } else {
                checkboxes.prop('checked', false);
            }
    });
})