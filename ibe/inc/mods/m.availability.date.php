<?
/*
 * Revised: May 11, 2011
 */
?>

<div style="text-align:center;padding-bottom:10px">
    <span style='font-size:16px;'>Today’s date: <? print date("l, F j, Y", strtotime($_TODAY)) ?></span>
    <div style='padding-top:20px'>
        <span class="button">Check Future Date</span><br>
        <span style='clear:both'>By choosing a date different than today, this form will be reset and you will not be able to finalize the booking. It is used for testing only.</span><br>
        <input type="hidden" id="RES_FUTURE_DATE" name="RES_FUTURE_DATE" value="<? print $_DATA['RES_FUTURE_DATE'] ?>" />
    </div>
</div>

<fieldset _style='display:none' id="RES_DATE_WRAP">
    <legend>Reservation Date</legend>
    <div class="fieldset">
        <div style='font-size:16px;'>Today’s date: <? print date("l, F j, Y", strtotime($_TODAY)) ?></div>
        <div class="label">
            <table align="center">
            <tr>
                <td>&nbsp;</td>
                <td style='padding-left:100px'>&nbsp;</td>
                <td>
                    <input type="hidden" id="RES_DATE" name="RES_DATE" value="<? print $_DATA['RES_DATE'] ?>" />
                </td>
            </tr>
            <tr>
                <td valign='top'><? print $clsUsers->propertiesRadioBtns($db, $_DATA['RES_PROP_ID']) ?></td>
                <td></td>
                <td valign='top'><div id="objRES_DATE"></div></td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sRES_DATE;

    ng.ready(function(){

    });

    function openResDateCalendar() {
        sRES_DATE = new ng.Calendar({
            input: 'RES_DATE',
            start_date: '<? print $_TODAY ?>',
            display_date: '<? print $_DATA['RES_DATE'] ?>',
            visible: true,
            object: "objRES_DATE",
            events: {
                onLoad: function() { $("#objRES_DATE .ng_cal_date_<? print ng_date(isset($_DATA['RES_DATE'])&&$_DATA['RES_DATE']!="0000-00-00 00:00:00" ? $_DATA['RES_DATE'] : "") ?>").addClass("ng_cal_selected_date").click(); }
            }
        })
    }
</script>
