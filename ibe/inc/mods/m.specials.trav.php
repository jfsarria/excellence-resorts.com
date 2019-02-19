<?
/*
 * Revised: Jun 24, 2011
 */
?>
<fieldset>
    <legend>Travel Period</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="TRAVEL_FROM" name="TRAVEL_FROM" value="<? print isset($_DATA['TRAVEL_FROM']) ? $_DATA['TRAVEL_FROM'] : "" ?>" /></td>
                <td style="padding-left:100px"></td>
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="TRAVEL_TO" name="TRAVEL_TO" value="<? print isset($_DATA['TRAVEL_TO']) ? $_DATA['TRAVEL_TO'] : "" ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="objTRAVEL_FROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objTRAVEL_TO"></div></td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sTravelFrom,
        sTravelTo

    ng.ready(function(){
        sTravelFrom = new ng.Calendar({
            input: 'TRAVEL_FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['TRAVEL_FROM'])&&$_DATA['TRAVEL_FROM']!="0000-00-00 00:00:00") ? $_DATA['TRAVEL_FROM'] : $_TODAY ?>',
            visible: true,
            object: "objTRAVEL_FROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objTRAVEL_FROM .ng_cal_date_<? print ng_date(isset($_DATA['TRAVEL_FROM'])&&$_DATA['TRAVEL_FROM']!="0000-00-00 00:00:00" ? $_DATA['TRAVEL_FROM'] : "") ?>")); ibe.page.height(); } 
            }
        });
        sTravelTo = new ng.Calendar({
            input: 'TRAVEL_TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['TRAVEL_TO'])&&$_DATA['TRAVEL_TO']!="0000-00-00 00:00:00") ? $_DATA['TRAVEL_TO'] : $_TODAY ?>',
            visible: true,
            object: "objTRAVEL_TO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objTRAVEL_TO .ng_cal_date_<? print ng_date(isset($_DATA['TRAVEL_TO'])&&$_DATA['TRAVEL_TO']!="0000-00-00 00:00:00" ? $_DATA['TRAVEL_TO'] : "") ?>")); ibe.page.height(); } 
            }
        });
    });
</script>
