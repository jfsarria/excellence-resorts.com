<?
/*
 * Revised: May 11, 2011
 */
?>

<? if ($_DATA['RES_IN_THE_FUTURE']==1) { ?>
    <fieldset class='reserv_fieldset'>
        <legend>Check Future Date</legend>
        <div class="fieldset">
            <div class="label">
                <table width='100%'>
                <tr>
                    <td style='width:300px' valign='top'><div id="objRES_DATE"></div></td>
                    <td>By choosing a date different than today, this form will be reset and you will not be able to finalize the booking. It is used for testing only.</td>
                </tr>
                </table>
                
            </div>
        </div>
        <script type="text/javascript">
            /* http://nogray.com/calendar.php */
            var sRES_DATE;

            ng.ready(function(){
                sRES_DATE = new ng.Calendar({
                    input: 'RES_DATE',
                    start_date: '<? print $_TODAY ?>',
                    display_date: '<? print $_DATA['RES_DATE'] ?>',
                    visible: true,
                    object: "objRES_DATE",
                    events: {
                        onLoad: function() { 
                            $("#objRES_DATE .ng_cal_date_<? print ng_date(isset($_DATA['RES_DATE'])&&$_DATA['RES_DATE']!="0000-00-00 00:00:00" ? $_DATA['RES_DATE'] : "") ?>").addClass("ng_cal_selected_date").click(); 
                            ibe.page.height();
                        }
                    }
                })
            });
        </script>
    </fieldset>
<? } ?>