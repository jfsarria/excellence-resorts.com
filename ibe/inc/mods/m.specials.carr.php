<?
/*
 * Revised: May 05, 2011
 */

$result = $clsSpecials->getClosed($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true)); 
$CLOSED_DATES = array_keys($result);
$CLOSED_ARRIVAL_CUR = implode(",", $CLOSED_DATES);

?>
<fieldset>
    <legend>Calendar Days Closed for Arrival</legend>
    <div class="fieldset">
        <div class="label">
            <br>
            <table align="left" id="CloseArrivalTbl">
            <tr>
                <td valign="top" style="padding-right:50px">
                    <div style="display:none">
                        <textarea id="CLOSED_ARRIVAL_NEW" name="CLOSED_ARRIVAL_NEW"></textarea>
                        <textarea id="CLOSED_ARRIVAL_CUR" name="CLOSED_ARRIVAL_CUR"><? print $CLOSED_ARRIVAL_CUR ?></textarea>
                    </div>
                    <div id="objCLOSED_ARRIVAL"></div>
                </td>
                <td valign="top" width="100%">
                    <table class="pickList" width='100%' border="0" cellpadding="2" cellspacing="2">
                    <tr>
                    <?
                    $cnt=0;
                    foreach ($CLOSED_DATES as $key => $value) {
                        $arr = explode(" ",$value);
                        $DATE = $arr[0];
                        print "
                            <td width='33%' nowrap id='dds_{$DATE}'>
                                <div class='aleft cbdelete' rel='{$DATE}'><img src='css/img/cross.png' width='16' height='16' border='0' alt='Delete'></div>
                                <div class='aleft'>&nbsp;".date("D, d M Y", strtotime($DATE))."</div>
                                <div class='aclear'></div>
                                <div style='display:none'><input id='cb_{$DATE}' class='checkbox' type='checkbox' name='CLOSED_ARRIVAL_DEL[]' value='{$DATE}' style='width:auto'></div>
                            </td>
                        ";
                        if (fmod(++$cnt,3)==0) print "</tr><tr>";
                    }
                    ?>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<script type="text/javascript">
    /* http://nogray.com/calendar.php */
    var sClosedArrival,
        sCLOSED_ARRIVAL = '<? print $CLOSED_ARRIVAL_CUR ?>';

    ng.ready(function(){
        sClosedArrival = new ng.Calendar({
            input: 'CLOSED_ARRIVAL_NEW',
            start_date: 'year - 5',
            display_date:sCLOSED_ARRIVAL,
            visible: true,
            object: "objCLOSED_ARRIVAL",
            multi_selection:true,
            server_date_format:'Y-m-d',
            hide_clear_button:true,
            hide_view_all_dates_button:true,
            events: {
                onLoad: function() { ibe.ng_cal_selected_date(sCLOSED_ARRIVAL, "#objCLOSED_ARRIVAL"); ibe.page.height(); },
                onMonthChange: function() { ibe.ng_cal_selected_date(sCLOSED_ARRIVAL, "#objCLOSED_ARRIVAL"); }
            }
        });
    });
</script>
