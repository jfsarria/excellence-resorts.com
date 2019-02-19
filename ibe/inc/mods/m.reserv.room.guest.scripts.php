    <? 
    if (isset($RES_LANGUAGE)) print "$('#RES_GUEST_LANGUAGE').val('{$RES_LANGUAGE}'); \n";
    if (isset($GUEST['COUNTRY'])) print "$('#RES_GUEST_COUNTRY').val('{$GUEST['COUNTRY']}') \n";
    if (isset($GUEST['MAILING_LIST'])) print "$('#MAILING_LIST').val('{$GUEST['MAILING_LIST']}') \n";
    ?>

    function setGuestStateDropDown() {
        var RES_GUEST_COUNTRY = $('#RES_GUEST_COUNTRY').val(),
            RES_GUEST_COUNTRY_TEXT = $("#RES_GUEST_COUNTRY option[value='"+RES_GUEST_COUNTRY+"']").text(),
            RES_GUEST_STATE_TEXT = $("#"+RES_GUEST_COUNTRY+"_GUEST_STATES option[value='"+$('#RES_GUEST_STATE').val()+"']").text();

        if (RES_GUEST_STATE_TEXT=="") RES_GUEST_STATE_TEXT = $("#"+RES_GUEST_COUNTRY+"_GUEST_STATES option").first().text();

        $("#RES_GUEST_COUNTRY").parent().find(".ui-btn-text").html(RES_GUEST_COUNTRY_TEXT);

        $('#US_GUEST_STATES,#CA_GUEST_STATES,#MX_GUEST_STATES,#RES_GUEST_STATE').hide();
        $('#US_GUEST_STATES,#CA_GUEST_STATES,#MX_GUEST_STATES,#RES_GUEST_STATE').parents(".ui-select").hide();

        $('#'+RES_GUEST_COUNTRY+'_GUEST_STATES').show();
        $('#'+RES_GUEST_COUNTRY+'_GUEST_STATES').parents(".ui-select").show();
        $('#'+RES_GUEST_COUNTRY+'_GUEST_STATES').parent().find(".ui-btn-text").html(RES_GUEST_STATE_TEXT);

        if (RES_GUEST_COUNTRY!="US"&&RES_GUEST_COUNTRY!="MX"&&RES_GUEST_COUNTRY!="CA") $('#RES_GUEST_STATE').show();
    }

    $('#RES_GUEST_COUNTRY').change(function(){
        setGuestStateDropDown();
    });
    $('#US_GUEST_STATES,#CA_GUEST_STATES,#MX_GUEST_STATES').change(function(){
        $('#RES_GUEST_STATE').val($(this).val());
    });
    setGuestStateDropDown();
    <? if (isset($GUEST['STATE'])) print "$('#US_GUEST_STATES,#CA_GUEST_STATES,#MX_GUEST_STATES,#RES_GUEST_STATE').val('{$GUEST['STATE']}'); \n"; ?>

    $('#GUEST_STATES').show();
    $('#GUEST_STATES').parents(".ui-select").show();
