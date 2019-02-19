<?
/*
 * Revised: May 05, 2011
 */

$result = $clsSpecials->getBlackout($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true)); 
$BLACKOUT_DATES = array_keys($result);
$BLACKOUT_CUR = implode(",", $BLACKOUT_DATES);

?>
<fieldset>
    <legend>Calendar Days Closed for Special</legend>
    <div class="fieldset">
        <i>Blackout days</i>
        <div class="label">
            <br>
            <table align="left" id="BlackoutTbl">
            <tr>
                <td valign="top" style="padding-right:50px">
                    <div style="display:none">
                        <textarea id="BLACKOUT_NEW" name="BLACKOUT_NEW"></textarea>
                        <textarea id="BLACKOUT_CUR" name="BLACKOUT_CUR"><? print $BLACKOUT_CUR ?></textarea>
                    </div>
                    <div id="objBLACKOUT"></div>
                </td>
                <td valign="top" width="100%">
                    <table class="pickList" width='100%' border="0" cellpadding="2" cellspacing="2">
                    <tr>
                    <?
                    $cnt=0;
                    foreach ($BLACKOUT_DATES as $key => $value) {
                        $arr = explode(" ",$value);
                        $DATE = $arr[0];
                        print "
                            <td width='33%' nowrap id='dds_bout{$DATE}'>
                                <div class='aleft cbdelete' rel='bout{$DATE}'><img src='css/img/cross.png' width='16' height='16' border='0' alt='Delete'></div>
                                <div class='aleft'>&nbsp;".date("D, d M Y", strtotime($DATE))."</div>
                                <div class='aclear'></div>
                                <div style='display:none'><input id='cb_bout{$DATE}' class='checkbox' type='checkbox' name='BLACKOUT_DEL[]' value='{$DATE}' style='width:auto'></div>
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
    var sBlackout,
        sBLACKOUT = '<? print $BLACKOUT_CUR ?>';

    ng.ready(function(){
        sBlackout = new ng.Calendar({
            input: 'BLACKOUT_NEW',
            start_date: 'year - 5',
            display_date:sBLACKOUT,
            visible: true,
            object: "objBLACKOUT",
            multi_selection:true,
            server_date_format:'Y-m-d',
            hide_clear_button:true,
            hide_view_all_dates_button:true,
            events: { 
                onLoad: function() { ibe.ng_cal_selected_date(sBLACKOUT, "#objBLACKOUT"); ibe.page.height(); },
                onMonthChange: function() { ibe.ng_cal_selected_date(sBLACKOUT, "#objBLACKOUT"); }
            }
        });
    });
</script>
