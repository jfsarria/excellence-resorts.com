<?
/*
 * Revised: Jun 24, 2011
 */
?>
<? if ($_DATA['RES_LANGUAGE']!="") { ?>
    <fieldset class='reserv_fieldset'>
        <legend>Reservation Dates</legend>
        <div class="fieldset">
            <div class="label">
                <table align="center">
                <tr>
                    <td valign="top"><b>Check-In<br><input type="hidden" id="RES_CHECK_IN" name="RES_CHECK_IN" value="<? print $_DATA['RES_CHECK_IN'] ?>" /></td>
                    <td style="padding-left:50px"></td>
                    <td valign="top"><b>Nights</b>&nbsp;</td>
                    <td style="padding-left:50px"></td>
                    <td valign="top"><b>Check-Out</b><br><input type="hidden" id="RES_CHECK_OUT" name="RES_CHECK_OUT" value="<? print $_DATA['RES_CHECK_OUT'] ?>" /></td>
                </tr>
                <tr>
                    <td colspan="1"><div id="objRES_CHECK_IN"></div></td>
                    <td></td>
                    <td valign="top"><input type="text" id="RES_NIGHTS" name="RES_NIGHTS" value="<? print $_DATA['RES_NIGHTS'] ?>" class="small" onBlur="ibe.callcenter.reviewNightsInput('RES_CHECK_IN', 'RES_CHECK_OUT', 'RES_NIGHTS');ibe.callcenter.addDaysToCalendar('RES_CHECK_IN', 'RES_CHECK_OUT', 'RES_NIGHTS')"></td>
                    <td></td>
                    <td colspan="1"><div id="objRES_CHECK_OUT"></div></td>
                </tr>
                </table>
            </div>
        </div>

        <script type="text/javascript">
            /* http://nogray.com/calendar.php */
            var sCheckIn,
                sCheckOut,
                sDatesAdjusted = false;

            ng.ready(function(){
                sCheckIn = new ng.Calendar({
                    input: 'RES_CHECK_IN',
                    start_date: '<? print $_TODAY ?>',
                    display_date: '<? print $_DATA['RES_CHECK_IN'] ?>',
                    visible: true,
                    server_date_format:'Y-m-d',
                    object: "objRES_CHECK_IN",
                    events: { 
                        onLoad: function() { ibe.calendarClick($("#objRES_CHECK_IN .ng_cal_date_<? print ng_date(isset($_DATA['RES_CHECK_IN'])&&$_DATA['RES_CHECK_IN']!="0000-00-00 00:00:00" ? $_DATA['RES_CHECK_IN'] : "") ?>")); ibe.page.height(); },
                        onDateClick: function(date) { 
                            ibe.callcenter.addDaysToCalendar('RES_CHECK_IN', 'RES_CHECK_OUT', 'RES_NIGHTS')
                        }
                    }
                });
                sCheckOut = new ng.Calendar({
                    input: 'RES_CHECK_OUT',
                    start_date: '<? print $_TODAY ?>',
                    display_date: '<? print $_DATA['RES_CHECK_OUT'] ?>',
                    server_date_format:'Y-m-d',
                    visible: true,
                    object: "objRES_CHECK_OUT",
                    events: { 
                        onLoad: function() { ibe.calendarClick($("#objRES_CHECK_OUT .ng_cal_date_<? print ng_date(isset($_DATA['RES_CHECK_OUT'])&&$_DATA['RES_CHECK_OUT']!="0000-00-00 00:00:00" ? $_DATA['RES_CHECK_OUT'] : "") ?>")); ibe.page.height(); },
                        onDateClick: function(date) { ibe.callcenter.adjustNights('RES_CHECK_IN','RES_CHECK_OUT','RES_NIGHTS') }
                    }
                });
            });
        </script>

    </fieldset>
<? } ?>
