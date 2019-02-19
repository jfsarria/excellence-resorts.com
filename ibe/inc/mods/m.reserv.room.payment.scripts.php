    function setPaymentStateDropDown() {
        var RES_CC_BILL_COUNTRY = $('#RES_CC_BILL_COUNTRY').val(),
            RES_CC_BILL_COUNTRY_TEXT = $("#RES_CC_BILL_COUNTRY option[value='"+RES_CC_BILL_COUNTRY+"']").text(),
            RES_CC_BILL_STATE = $("#"+RES_CC_BILL_COUNTRY+"_PAYMENT_STATES option[value='"+$('#RES_GUEST_STATE').val()+"']").text();

        if (RES_CC_BILL_STATE=="") RES_CC_BILL_STATE = $("#"+RES_CC_BILL_COUNTRY+"_PAYMENT_STATES option").first().text();

        $("#RES_CC_BILL_COUNTRY").parent().find(".ui-btn-text").html(RES_CC_BILL_COUNTRY_TEXT);

        $('#US_PAYMENT_STATES,#CA_PAYMENT_STATES,#MX_PAYMENT_STATES,#RES_CC_BILL_STATE').hide();
        $('#US_PAYMENT_STATES,#CA_PAYMENT_STATES,#MX_PAYMENT_STATES,#RES_CC_BILL_STATE').parents(".ui-select").hide();

        $('#'+RES_CC_BILL_COUNTRY+'_PAYMENT_STATES').show();
        $('#'+RES_CC_BILL_COUNTRY+'_PAYMENT_STATES').parents(".ui-select").show();
        $('#'+RES_CC_BILL_COUNTRY+'_PAYMENT_STATES').parent().find(".ui-btn-text").html(RES_CC_BILL_STATE);

        if (RES_CC_BILL_COUNTRY!="US"&&RES_CC_BILL_COUNTRY!="MX"&&RES_CC_BILL_COUNTRY!="CA") $('#RES_CC_BILL_STATE').show();
    }

    $('#RES_CC_BILL_COUNTRY').change(function(){
        setPaymentStateDropDown();
    });
    $('#US_PAYMENT_STATES,#CA_PAYMENT_STATES,#MX_PAYMENT_STATES').change(function(){
        $('#RES_CC_BILL_STATE').val($(this).val());
    });
    setPaymentStateDropDown();
    <? if (isset($CC_BILL_STATE)) print "$('#US_PAYMENT_STATES,#CA_PAYMENT_STATES,#MX_PAYMENT_STATES').val('{$CC_BILL_STATE}'); \n"; ?>

    $('#PAYMENT_STATES').show();
    $('#PAYMENT_STATES').parents(".ui-select").show();
