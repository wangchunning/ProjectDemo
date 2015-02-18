$(function(){

    // 点击日期搜索提交按钮
    $('#date_search').click(function(){
        $('#fancybox-loading div').css({'background':'none','text-align':'center'}).html('<i style="display:block;color:#fff;line-height:44px;font-size:25px;" class="fa fa-check"></i>');
        var url = $('form.date-pick').attr('action');
        window.location.href = url + '&start=' + $('#start').val() + '&expriy=' + $('#expriy').val();
    });

    // 点击 search 搜索提交按钮
    $('#text_search').click(function(){
        var url = $('form.text-search').attr('action');
        window.location.href = url + '&search=' + $('#search').val();
    })
})