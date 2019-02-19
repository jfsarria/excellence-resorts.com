<?
/*
 * Revised: Jun 24, 2011
 */
?>
<fieldset>
    <legend>Booking Window</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td><b>From</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="BOOK_FROM" name="BOOK_FROM" value="<? print isset($_DATA['BOOK_FROM']) ? $_DATA['BOOK_FROM'] : "" ?>" /></td>
                <td style="padding-left:100px"></td>
                <td><b>To</b>&nbsp;</td>
                <td align="right"><input type="hidden" id="BOOK_TO" name="BOOK_TO" value="<? print isset($_DATA['BOOK_TO']) ? $_DATA['BOOK_TO'] : "" ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="objBOOK_FROM"></div></td>
                <td></td>
                <td colspan="2"><div id="objBOOK_TO"></div></td>
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
            input: 'BOOK_FROM',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['BOOK_FROM'])&&$_DATA['BOOK_FROM']!="0000-00-00 00:00:00") ? $_DATA['BOOK_FROM'] : $_TODAY ?>',
            visible: true,
            object: "objBOOK_FROM",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objBOOK_FROM .ng_cal_date_<? print ng_date(isset($_DATA['BOOK_FROM'])&&$_DATA['BOOK_FROM']!="0000-00-00 00:00:00" ? $_DATA['BOOK_FROM'] : "") ?>")); ibe.page.height(); } 
            }
        });
        sBookTo = new ng.Calendar({
            input: 'BOOK_TO',
            start_date: 'year - 5',
            display_date: '<? print (isset($_DATA['BOOK_TO'])&&$_DATA['BOOK_TO']!="0000-00-00 00:00:00") ? $_DATA['BOOK_TO'] : $_TODAY ?>',
            visible: true,
            object: "objBOOK_TO",
            events: { 
                onLoad: function() { ibe.calendarClick($("#objBOOK_TO .ng_cal_date_<? print ng_date(isset($_DATA['BOOK_TO'])&&$_DATA['BOOK_TO']!="0000-00-00 00:00:00" ? $_DATA['BOOK_TO'] : "") ?>")); ibe.page.height(); } 
            }
        });
    });
</script>
