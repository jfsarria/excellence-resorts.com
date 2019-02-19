<?
/*
 * Revised: Oct 06, 2011
 *          May 18, 2016
 *          Aug 15, 2016
 */

$RES_ROOMS_SELECTED = (isset($_POST['RES_ROOMS_SELECTED'])) ? $_POST['RES_ROOMS_SELECTED'] : array();

$RES_NIGHTS = (int)$_AVAILABILITY['RES_NIGHTS'];
$IS_ALL_AVAILABLE = true;
for ($ROOM_NUM=1; $ROOM_NUM <= (int)$_AVAILABILITY['RES_ROOMS_QTY']; ++$ROOM_NUM) { 
    //print "<br>".$ROOM_NUM." --> ".count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"]);
    $IS_ALL_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;
    if (!$IS_ALL_AVAILABLE) {
        break;
    } else {
        foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
            $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
            $IS_ALL_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;
            //print "<br> * $AVAILABLE_NIGHTS == $RES_NIGHTS";
            if ($IS_ALL_AVAILABLE) {
                break;
            }
        }
        if (!$IS_ALL_AVAILABLE) {
            break;
        }
    }
}

ob_start();

?>
<fieldset id='avaResults'>
    <legend>Availability Results</legend>
    <div class="fieldset hdr">
    <script>
        var CURRENCY = <? print json_encode($_AVAILABILITY['CURRENCY']) ?>
    </script>
    <? 
    if ($IS_ALL_AVAILABLE) { ?>
        <table width='100%'>
        <tr>
            <td align='center'>
            <?
                print "
                    {$_AVAILABILITY['RES_ITEMS']['PROPERTY']['NAME']}<br>
                    ".date("F j, Y", strtotime($_AVAILABILITY['RES_CHECK_IN']))." - ".date("F j, Y", strtotime($_AVAILABILITY['RES_CHECK_OUT']))."<br>
                    {$_AVAILABILITY['RES_ROOMS_QTY']} room".((int)$_AVAILABILITY['RES_ROOMS_QTY']>1?"s":"")." - {$RES_NIGHTS} night".($RES_NIGHTS>1?"s":"")."
                ";
            ?>
            </td>
            <td align='center' class='TOTAL_RESERVATION_CELL'>
                <div>
                  <div class="TOTAL_CONVERSION" style="float:left"></div>
                  <div style="float:left">
                    See Prices in: 
                    <select id="QUOTE" rel="USDUSD" onchange="ibe.quote_Change(this)">
                        <option value="USDUSD">USD</option>
                        <?
                          $CODES = ARRAY('CAD','AUD','GBP','EUR','MXN','BRL');
                          foreach ($CODES as $CODE) {
                            print "<option value='USD{$CODE}' >{$CODE}</option>";
                          }
                        ?>
                    </select>
                  </div> 
                  <div style="clear:both"></div>
                </div>
                <div><div class="TOTAL_RESERVATION" style="float:left"></div><div style="float:left">&nbsp;&nbsp;(<span id="conversion_code">USD</span>)</div> <div style="clear:both"></div> </div>

                <? if ((int)$_AVAILABILITY['RES_IN_THE_FUTURE']==0) { ?>
                <div style='padding-top:10px;'><span class="button" onclick='$("#reservfrm").submit()'>Book Selected &#187;</span></div>
                <? } ?>
            </td>
        </tr>
        </table>
    <? } else { ?>

        <center><strong style="color:#990000">Not all rooms are available</strong></center>

    <? } ?>
    </div>
</fieldset>
<? 
$AVA_HEADER = ob_get_clean();
?>
<form id="reservfrm" method="post" enctype="multipart/form-data" action="?PAGE_CODE=reserv&PAGE_SECTION=forwhom">
    <input type="hidden" name="RES_ROOMS_SELECTED" id="RES_ROOMS_SELECTED" VALUE="">
    <input type="hidden" name="CURRENCY_CODE" id="CURRENCY_CODE" VALUE="">
    <input type="hidden" name="CURRENCY_QUOTE" id="CURRENCY_QUOTE" VALUE="1">
    
<?
print "<a name='results'></a><div style='margin-top:30px'>{$AVA_HEADER}</div>";

if (count($_AVAILABILITY['RES_ITEMS']['MESSAGES'])!=0) {
    print "<p class='s_error top_msg'>".implode("<br>",$_AVAILABILITY['RES_ITEMS']['MESSAGES'])."</p>";
}

$TOOLTIP = array();
for ($ROOM_NUM=1; $ROOM_NUM <= (int)$_AVAILABILITY['RES_ROOMS_QTY']; ++$ROOM_NUM) { 
    $ROOM_ORDER = 0;
    $IS_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;
    $ADULTS_QTY = (int)$_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"];
    $CHILDREN_QTY = (isset($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"])) ? (int)$_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"] : 0;
    ?>
    <fieldset>
        <legend>
            Room <? print $ROOM_NUM ?> 
            <? 
                if ($IS_AVAILABLE) { 
                    print ", ".$ADULTS_QTY." Adult".($ADULTS_QTY>1?"s":"");
                    if ($CHILDREN_QTY!=0) print ", ".$CHILDREN_QTY." child".($CHILDREN_QTY>1?"ren":"");
                }
            ?> 
        </legend>
        <div class="fieldset">
        <? if ($IS_AVAILABLE) { ?>
            <table id='availableRooms_<? print $ROOM_NUM ?>' class='avaResultsTbl' width="100%">
            <tr class="hdr">
                <td></td>
                <td nowrap>Rate Name<br>or Special</td>
                <td nowrap>Average Rate<br>per Night</td>
                <td nowrap>Rate Diff.<br>w/Selected</td>
                <td nowrap>Total Rate</td>
                <td></td>
            </tr>
            <? 
            $cnt = 0;
            foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
                $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
                $IS_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;

                $ROOM_DESCR = $_AVAILABILITY['RES_ITEMS'][$ROOM_ID]['DESCR_'.$_AVAILABILITY['RES_LANGUAGE']];
                $ROOM_INCLU = $_AVAILABILITY['RES_ITEMS'][$ROOM_ID]['INCLU_'.$_AVAILABILITY['RES_LANGUAGE']];
                $ROOM_CONT = $ROOM_DESCR.((trim($ROOM_INCLU)!="")?"<br><br><b>Inclusions:</b><br>".$ROOM_INCLU:"");

                $room_descr_ID = "room_descr_{$ROOM_ID}";
                $TOOLTIP[$room_descr_ID] = array("CLS"=>"tooltip_room_descr","CONT"=>$ROOM_CONT);

                $daily_details_ID = "daily_details_{$ROOM_NUM}_{$ROOM_ID}";
                $TOOLTIP[$daily_details_ID] = array("CLS"=>"tooltip_daily_details","CONT"=>$clsGlobal->daily_rate_details($_AVAILABILITY, array("ROOM_ID"=>$ROOM_ID,"ROOM_NUM"=>$ROOM_NUM)));

                if ((int)$_AVAILABILITY["RES_ITEMS"][$ROOM_ID]["ROOM_ORDER"]==10 && $ROOM_ORDER==0) { 
                    $ROOM_ORDER = 10; ?>
                    <tr class='IS_VIP'>
                        <td colspan='10' align='center'><i>FINEST CLUB</i></td>
                    </tr>
                <? }

                if ((int)$_AVAILABILITY["RES_ITEMS"][$ROOM_ID]["ROOM_ORDER"]==20 && ($ROOM_ORDER==10 || $ROOM_ORDER==0)) { 
                    $ROOM_ORDER = 20; ?>
                    <tr class='IS_VIP'>
                        <td colspan='10' align='center'><i>EXCELLENCE CLUB</i></td>
                    </tr>
                <? }

                if ($IS_AVAILABLE) { 
                    ++$cnt;
                    $CLASS_SPECIAL = (is_array($ROOM['SPECIAL_NAMES'])) ? $ROOM['SPECIAL_NAMES'] : $ROOM['CLASS_NAMES'];
                    $AVG_GROSS = (int)$ROOM['TOTAL']['AVG_GROSS_PN'];
                    $AVG_FINAL = (int)$ROOM['TOTAL']['AVG_FINAL_PN'];
                    $GROSS = (int)$ROOM['TOTAL']['GROSS'];
                    $FINAL = (int)$ROOM['TOTAL']['FINAL'];
                    ?>
                    <tr id='room_id_<? print $ROOM_ID ?>' class='AVAILABLE_ROOM<? if ($cnt==1) print " selected" ?>'>
                        <td width="50%"><a rel="tooltip" tootip="<? print $room_descr_ID ?>"><? print $ROOM['NAME'] ?></a></td>
                        <td width="100%">
                        <? 
                        foreach ($CLASS_SPECIAL AS $TID=>$REFERENCE) {
                            $TTID = "room_special_{$ROOM_ID}_{$TID}";
                            $CONT = $_AVAILABILITY['RES_ITEMS'][$TID]['DESCR_'.$_AVAILABILITY['RES_LANGUAGE']];
                            $TOOLTIP[$TTID] = array("CLS"=>"tooltip_room_descr","CONT"=>$CONT);
                            $NAME = $_AVAILABILITY['RES_ITEMS'][$TID]['NAME_'.$_AVAILABILITY['RES_LANGUAGE']];
                            print "<div><a rel='tooltip' tootip='{$TTID}'>{$NAME}</a></div>";
                        }
                        ?>
                        </td>
                        <td>
                            <? if ($AVG_GROSS!=$AVG_FINAL) print "<div class='room_currency crossed' data-usd='$AVG_GROSS' rel='$AVG_GROSS'>$".number_format($AVG_GROSS)."</div>"; ?>
                            <div class='room_currency AVG_FINAL' data-usd='<?=$AVG_FINAL?>' rel='<?=$AVG_FINAL?>'>$<? print number_format($AVG_FINAL) ?></div>
                        </td>
                        <td><div class='AVG_FINAL_DIFF'></div></td>
                        <td>
                            <? if ($GROSS!=$FINAL) print "<div class='room_currency crossed' data-usd='$GROSS' rel='$GROSS'>$".number_format($GROSS)."</div>"; ?>
                            <div class='room_currency FINAL' data-usd='<?=$FINAL?>' rel='<?=$FINAL?>'><a rel="tooltip" tootip="<? print $daily_details_ID ?>">$<? print number_format($FINAL) ?></a></div>
                        </td>
                        <td nowrap><input type="radio" name="SELECTED_ROOM_<? print $ROOM_NUM ?>" <? if ($cnt==1) print "checked" ?> value="<? print $ROOM_NUM."-".$ROOM_ID ?>" onClick="ibe.availability.selectRoom('<? print $ROOM_NUM ?>','<? print $ROOM_ID ?>')"> Select</td>
                    </tr>
                <? } else { ?>
                    <tr id='room_id_<? print $ROOM_ID ?>' class='NO_AVAILABLE_ROOM'>
                        <td width="50%"><a rel="tooltip" tootip="<? print $room_descr_ID ?>"><? print $ROOM['NAME'] ?></a></td>
                        <td colspan='10' align='center'><a rel="tooltip" tootip="<? print $daily_details_ID ?>"><i>ROOM NOT AVAILABLE</i></a></td>
                    </tr>
                <? 
                }
            } ?>
            </table>
            <script>
                <? 
                if (isset($RES_ROOMS_SELECTED[$ROOM_NUM-1])) { ?>
                    $("#availableRooms_<? print $ROOM_NUM ?> tr#room_id_<? print $RES_ROOMS_SELECTED[$ROOM_NUM-1] ?> input[type='radio']").click();
                <? } else { ?>
                    $("#availableRooms_<? print $ROOM_NUM ?> tr.selected input[type='radio']").click();
                <? } ?>
            </script>
        <? } else { ?>
            <center><b>There is not availability for the given search.</b></center>
        <? } ?>
        </div>
    </fieldset>
    <?
    foreach ($TOOLTIP AS $TTID=>$TIP) {
        print "<div id='{$TTID}' style='display:none'><div class='{$TIP['CLS']}'>{$TIP['CONT']}</div></div>";
    }
} 
print $AVA_HEADER;
?>
</form>
<script>
    ibe.availability.totalReservation();
</script>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
