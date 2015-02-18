
$('#swift_code').blur(function(){
    var swift_code = $(this).val();
    if (swift_code == '')
        return;

    $.fancybox.showLoading();
    $.ajax({
        url : '/valid/swiftcode-bank',
        dataType : 'json',
        data : {swiftcode : swift_code},
        type : 'GET',
        success : function(rep) {
            $.fancybox.hideLoading();

            $('#bank_name').attr('value',rep.bank);
            $('#bank_branch').attr('value',rep.branch);
            $('#bank_unit_number').attr('value',rep.unit_number);
            $('#bank_street_number').attr('value',rep.street_number);
            $('#bank_street').attr('value',rep.street);
            $('#bank_city').attr('value',rep.city);
            $('#bank_state').attr('value', rep.state);
            $('#bank_postcode').attr('value',rep.postcode);
                        
        }
    })

}); 