<?
/*
 * Revised: Feb 03, 2013
 */

?>
<table width="100%" cellspacing="2">
<tr>
    <td valign='top' width="20%" nowrap style="padding-right:20px">
        <fieldset>
            <legend>View Reservations at</legend>
            <div class="fieldset">
                <div class="label">
                    <? print $clsUsers->propertiesCheckBoxes($db, array("ELE_ID"=>"PROP_IDs","PROP_IDs"=>$PROP_IDs,"DEFAULT_ALL"=>true)); ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Include</legend>
            <div class="fieldset">
                <div class="label">
                    <? print $clsGlobal->reservTypesCheckBoxes($db, array("ELE_ID"=>"RESTYPE_IDs","RESTYPE_IDs"=>$RESTYPE_IDs,"DEFAULT_ALL"=>true)); ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Made by</legend>
            <div class="fieldset">
                <div class="label">
                    <? print $clsGlobal->getUserTypeCheckBoxes($db, array("ELE_ID"=>"MADEBY_IDs","MADEBY_IDs"=>$MADEBY_IDs,"DEFAULT_ALL"=>true)); ?>
                    <div id='callCenterCheckBoxes'>
                        <? print $clsGlobal->getCallCenterCheckBoxes($db, array("ELE_ID"=>"AGENT_IDs","AGENT_IDs"=>$AGENT_IDs,"DEFAULT_ALL"=>false)); ?>
                    </div>
                </div>
            </div>
        </fieldset>    
    </td>
    <td valign='top' width="80%">
        <fieldset>
            <legend>View By</legend>
            <div class="fieldset">
                <span><input type="radio" name="VIEWBY" value="activity" <? if ($VIEWBY=="activity") print "checked" ?>></span>&nbsp;IBE Activity Date
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span><input type="radio" name="VIEWBY" value="arrival" <? if ($VIEWBY=="arrival") print "checked" ?>></span>&nbsp;Arrival Date
            </div>
        </fieldset>

        <fieldset>
            <legend>Dates</legend>
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
                    <td width="50%">Reservation ID<br><input type="text" name="RESNUM" ID="RESNUM" style="width:240px" value="<? print isset($RESNUM) ? $RESNUM : "" ?>"></td>
                </tr>
                <tr>
                    <td width="50%">Contact Phone Number<br><input type="text" name="PHONE" ID="PHONE" style="width:240px" value="<? print isset($PHONE) ? $PHONE : "" ?>"></td>
                    <td width="50%" rowspan="2">
                        <a onclick="$('#ACTION').val('SUBMIT');$('#pageNo').val('1');$('#editfrm').submit()"><span class="button key">Search</span></a>
                    </td>
                </tr>
                <tr>
                    <td width="50%">Contact Email<br><input type="text" name="EMAIL" ID="EMAIL" style="width:240px" value="<? print isset($EMAIL) ? $EMAIL : "" ?>"></td>
                </tr>
                </table>
            </div>
        </fieldset>
    </td>
</tr>
</table>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sFrom,
        sTo

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
</script>

<div id='inventoryEditBox'></div>