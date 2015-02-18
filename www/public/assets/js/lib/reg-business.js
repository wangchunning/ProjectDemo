$(function(){

    //$('#abn').mask("999-999-999");

    $('#abn').blur(function(){
        if ($('#business_name').val() == '') {
            $('#abn').trigger('keyup');
        }
    });

    $('#abn').keyup(function(){
        if ($(this).val().indexOf('_') != -1) {
            $('#business_name').val('');
            $('#abn').closest('.form-group').removeClass('has-error has-success');
            $('#abn-label').html('');
            return false;
        }

        $.ajax({
            url : '/register/check-abn',
            type : 'POST',
            data : {abn : $('#abn').val()},
            dataType : 'json',
            beforeSend : function() {
                $('#abn-label').html('Searching ABN/ACN Number ... Please Wait...');    
            },
            success : function(rep) {
                if (rep.status == 'error') {
                    $('#abn').closest('.form-group').removeClass('has-success').addClass('has-error');
                    $('#abn-label').html(rep.data.msg);
                    return false;
                }

                $('#abn').closest('.form-group').removeClass('has-error').addClass('has-success');
                $('#abn-label').html('');
                $('#business_name').val(rep.data.name);
            }
        })
    })
})

