<?
/*
 * Revised: Jun 24, 2011
 */
?>
<fieldset>
    <legend>Season Dates</legend>
    <div class="fieldset">
        <div class="label" style="margin-top:5px;margin-bottom:20px;text-align: center;">
            Season Belongs to the Year&nbsp;
            <select id="YEAR" name="YEAR">
            <?
            for ($t=2011; $t<=date("Y")+2; ++$t) {
                $selected = (isset($_DATA['YEAR'])&&(int)$_DATA['YEAR']==$t) ? "selected":"";
                print "<option value='{$t}' $selected>{$t}</option>";
            }
            ?>
            </select>
        </div>
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="FROM" name="FROM" value="<? print isset($_DATA['FROM']) ? $_DATA['FROM'] : "" ?>" /></td>
                <td style="padding-left:100px"></td>
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="TO" name="TO" value="<? print isset($_DATA['TO']) ? $_DATA['TO'] : "" ?>" /></td>
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

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sFrom,
        sTo

    ng.ready(function(){
        sFrom = new ng.Calendar({
            input: 'FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00") ? $_DATA['FROM'] : $_TODAY ?>',
            visible: true,
            object: "objFROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objFROM .ng_cal_date_<? print ng_date(isset($_DATA['FROM'])&&$_DATA['FROM']!="0000-00-00 00:00:00" ? $_DATA['FROM'] : "") ?>")); ibe.page.height(); } 
            }
        });
        sTo = new ng.Calendar({
            input: 'TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['TO'])&&$_DATA['TO']!="0000-00-00 00:00:00") ? $_DATA['TO'] : $_TODAY ?>',
            visible: true,
            object: "objTO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objTO .ng_cal_date_<? print ng_date(isset($_DATA['TO'])&&$_DATA['TO']!="0000-00-00 00:00:00" ? $_DATA['TO'] : "") ?>")); ibe.page.height(); } 
            }
        });
    });
</script>
