

<fieldset>
    <legend>Travel Period</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="CAB_FROM" name="CAB_FROM" value="<? print isset($_DATA[0]['CAB_FROM']) ? $_DATA[0]['CAB_FROM'] : "" ?>" /></td>
                <td style="padding-left:100px"></td>
                <input type="hidden" id="RES_NIGHTS" name="RES_NIGHTS" value="0" class="small">
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="CAB_TO" name="CAB_TO" value="<? print isset($_DATA[0]['CAB_TO']) ? $_DATA[0]['CAB_TO'] : "" ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="objCAB_FROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objCAB_TO"></div></td>
            </tr>
            
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sBookFrom,
        sBookTo

    ng.ready(function(){
        sBookFrom = new ng.Calendar({
            input: 'CAB_FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA[0]['CAB_FROM'])&&$_DATA[0]['CAB_FROM']!="0000-00-00 00:00:00") ? $_DATA[0]['CAB_FROM'] : $_TODAY ?>',
            visible: true,
            object: "objCAB_FROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objCAB_FROM .ng_cal_date_<? print ng_date(isset($_DATA[0]['CAB_FROM'])&&$_DATA[0]['CAB_FROM']!="0000-00-00 00:00:00" ? $_DATA[0]['CAB_FROM'] : $_TODAY) ?>")); ibe.page.height(); },
                onDateClick: function(date) { 
                    ibe.callcenter.addDaysToCalendar('CAB_FROM', 'CAB_TO', 'RES_NIGHTS');
                }
            }
        });
        sBookTo = new ng.Calendar({
            input: 'CAB_TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA[0]['CAB_TO'])&&$_DATA[0]['CAB_TO']!="0000-00-00 00:00:00") ? $_DATA[0]['CAB_TO'] : $_TODAY ?>',
            visible: true,
            object: "objCAB_TO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objCAB_TO .ng_cal_date_<? print ng_date(isset($_DATA[0]['CAB_TO'])&&$_DATA[0]['CAB_TO']!="0000-00-00 00:00:00" ? $_DATA[0]['CAB_TO'] : $_TODAY) ?>")); ibe.page.height(); } 
            }
        });
    });
</script>
