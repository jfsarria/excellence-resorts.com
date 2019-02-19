<fieldset>
    <legend>Searching Dates (Default from: <?=$FROM?>)</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="FROM" name="FROM" value="<? print isset($FROM) ? $FROM : "" ?>" /></td>
                <td style="padding-left:10px"></td>
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="TO" name="TO" value="<? print isset($TO) ? $TO : "" ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="objFROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objTO"></div></td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Search By</legend>
    <div class="fieldset">
        <table width="100%">
        <tr>
            <td width="50%">Contact Last Name<br><input type="text" name="LASTNAME" ID="LASTNAME" style="width:240px" value="<? print isset($LASTNAME) ? $LASTNAME : "" ?>"></td>
            <td width="50%">
                <div id="props" style="width:200px"><? print $clsUsers->propertiesCheckBoxes($db, array("ELE_ID"=>"PROP_IDs","PROP_IDs"=>$PROP_IDs,"DEFAULT_ALL"=>false,"SHORT"=>true)); ?></div>
            </td>
        </tr>
        <tr>
            <td width="50%">Contact Phone Number<br><input type="text" name="PHONE" ID="PHONE" style="width:240px" value="<? print isset($PHONE) ? $PHONE : "" ?>"></td>
            <td width="50%">Reservation ID<br><input type="text" name="RESNUM" ID="RESNUM" style="width:240px" value="<? print isset($RESNUM) ? $RESNUM : "" ?>"></td>
        </tr>
        <tr>
            <td width="50%">Contact Email<br><input type="text" name="EMAIL" ID="EMAIL" style="width:240px" value="<? print isset($EMAIL) ? $EMAIL : "" ?>"></td>
            <td width="50%" rowspan="2">
                <a onclick="reviewPropIDs(); $('#ACTION').val('SUBMIT');$('#pageNo').val('1');$('#editfrm').submit()"><span class="button key">Search</span></a>
            </td>
        </tr>
        </table>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sFrom,
        sTo;

    /* year: <?=date("Y")?> */

    ng.ready(function(){
        sFrom = new ng.Calendar({
            input: 'FROM',
            start_date: 'year - 2',
            display_date: '<? print (isset($FROM)&&$FROM!="0000-00-00 00:00:00") ? $FROM : $_TODAY ?>',
            visible: true,
            object: "objFROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objFROM .ng_cal_date_<? print ng_date(isset($FROM)&&$FROM!="0000-00-00 00:00:00" ? $FROM : "") ?>")); ibe.page.height(); } 
            }
        });
        sTo = new ng.Calendar({
            input: 'TO',
            start_date: 'year - 2',
            display_date: '<? print (isset($TO)&&$TO!="0000-00-00 00:00:00") ? $TO : $_TODAY ?>',
            visible: true,
            object: "objTO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objTO .ng_cal_date_<? print ng_date(isset($TO)&&$TO!="0000-00-00 00:00:00" ? $TO : "") ?>")); ibe.page.height(); } 
            }
        });
    });

    function reviewPropIDs() {
        var RESNUM = $("#RESNUM").val().trim();
        if (RESNUM!="") {
            var ID = RESNUM.substr(0,1);
            if (typeof $("#PROP_IDs_"+ID).length == 1) {
              $("#PROP_IDs_"+ID)[0].checked = true;
            }
        }
    }

    function reviewDates() {
      var FROM = $("#FROM").val(),
          TO = $("#TO").val(),
          DIFF = ibe.dateDiff(FROM, TO);
      //alert(FROM + " - " + TO + " = " + DIFF)
      if (DIFF == 0 || DIFF > 31) {
        alert("Please select a date range between 1 and 31 days")
        return false;
      } else {
        return true;
      } 
    }

    if ($(".PROP_ID").length==1) {
        $(".PROP_ID")[0].checked = true;
    }

    $("#props .PROP_ID").click(function(){
        var $selected = $(this);
        $("#props .PROP_ID").attr('checked', false); // Unchecks it
        $selected.attr('checked', true); 
    })
</script>
