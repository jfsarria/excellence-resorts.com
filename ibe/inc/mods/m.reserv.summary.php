<?
/*
 * Revised: Jul 21, 2011
 *          Aug 15, 2016
 *          Sep 13, 2018
 */

//print_r($_POST);

$QUOTE = isset($_POST['CURRENCY_CODE']) ? $_POST['CURRENCY_CODE'] : "USDUSD";
$CONVERSION = isset($_POST['CURRENCY_QUOTE'])&&!empty($_POST['CURRENCY_QUOTE']) ? (double)$_POST['CURRENCY_QUOTE'] : 1;

$ROOM_NAMES = implode(", ",$RESERVATION['RES_ROOMS_SELECTED_NAMES']);
?>
<fieldset id='reserv_summary<? if ($isDone) print "_final" ?>'>
    <legend>
        <? print ($isDone) ? "Reservation Complete: #".$_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'] : "Reservation Summary" ?>
    </legend>
    <div>
        <h4><? print $RES_ITEMS['PROPERTY']['NAME'] ?></h4>
        <div>Check In Date: <? print date("F j, Y", strtotime($RES_CHECK_IN)) ?></div>
        <div>Check Out Date: <? print date("F j, Y", strtotime($RES_CHECK_OUT)) ?></div>
        <div>Number of Nights: <? print $RES_NIGHTS ?></div>
        <div>Number of Rooms: <? print $RES_ROOMS_QTY ?></div>
        <div>Number of Adults: <? print $RES_ROOMS_ADULTS_QTY ?></div>
        <? if ($RES_ROOMS_CHILDREN_QTY!=0) { ?>
        <div>Number of Children: <? print $RES_ROOMS_CHILDREN_QTY ?></div>
        <? } ?>
        <br>

        <div>Room Type(s): <? print $ROOM_NAMES; ?></div>
        <?
        foreach ($RESERVATION['RES_ROOM_CHARGE'] as $IND=>$ROOM_CHARGE) {
            print "Room ".($IND+1)." total charges: $".number_format($ROOM_CHARGE)."<br>";
        }
        ?>
        <div id="summary_total_room_charge" rel="<?=$RESERVATION['RES_TOTAL_CHARGE']?>">Total Room Charge: $<? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></div>
        <div id='summary_transfer'></div>

        <div id="total_conversion" rel="<?=$CONVERSION?>" style="padding-top:10px" _class="hidden">
            <div id="conv_lbl_total_usd">Equivalent Cost:</div>
            <div><span id="conv_total_is">$<?= number_format(ceil($RESERVATION['RES_TOTAL_CHARGE'] * $CONVERSION)) ?></span>&nbsp;(<span><? print str_replace("USD","",$QUOTE)?></span>)</div>
        </div>

        <div style='padding:20px 0;text-align:center'>
            <? if (!$isDone) { ?>
            <form id="modifyfrm" method="post" enctype="multipart/form-data" action="?#results">
                <?
                foreach ($_SESSION['AVAILABILITY']['SEARCH'] AS $KEY => $VALUE) {
                    if (is_array($VALUE)) {
                        foreach ($VALUE AS $SKEY => $SVALUE) {
                            if (!is_array($SVALUE)) print "<input type='hidden' name='{$KEY}[]' VALUE='{$SVALUE}'>\n";
                        }
                    } else {
                        print "<input type='hidden' name='{$KEY}' VALUE='{$VALUE}'>\n";
                    }
                }
                if (isset($_SESSION['AVAILABILITY']['RES_REBOOKING']) && isset($_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_ID']) && trim($_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_ID'])!="") {
                    foreach ($_SESSION['AVAILABILITY']['RES_REBOOKING'] AS $KEY => $VALUE) {
                        print "<input type='hidden' name='REBOOK_{$KEY}' VALUE='{$VALUE}'>\n";
                    }
                }
                ?>
            </form>
            <div>
                <a onclick="$('#modifyfrm').submit()"><span class="button key">Modify</span></a>
            </div>
            <? } else { 
               
                
            } ?>
        </div>

        <div><b>Cancellation and Modification Policy</b></div>
        <div><? print $clsReserv->getCancellationModificationPolicy($RES_CHECK_IN, $RES_LANGUAGE) ?></div>

    </div>
</fieldset>