<?
/*
 * Revised: Oct 20, 2011
 *          Jul 01, 2014
 *          Jul 15, 2014
 */


if ($MODIFY!="") {

    include "m.edit_reserv.modify.php";

} else {
    ?>
    <fieldset>
        <legend>Reservation Info</legend>
        <div class="fieldset">
            <div>Hotel: <? print $RES_ITEMS['PROPERTY']['NAME'] ?></div>
            <br>
            <div>No. of Rooms: <? print $RES_ROOMS_QTY ?></div>
            <div>No. of Adults: <? print $RES_ROOMS_ADULTS_QTY ?></div>
            <div>No. of Children: <? print ((int)$RES_ROOMS_CHILDREN_QTY - (int)$RES_ROOMS_INFANTS_QTY) ?></div>
            <div>No. of Infants: <? print $RES_ROOMS_INFANTS_QTY ?></div>
            <br>
            <div>Book Date: <? print shortDate($RESVIEW['CREATED']) ?></div>
            <div>Book Geo: <? print $GEO_INFO ?></div>
            <br>
            <div>CheckIn: <? print shortDate($RES_CHECK_IN) ?></div>
            <div>CheckOut: <? print shortDate($RES_CHECK_OUT) ?></div>
            <? foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $ind => $ROOM_ID) { 
                $ROOM = $JSON["RES_ROOM_".($ind+1)."_ROOMS"][$ROOM_ID];
                $CHILDREN = (int)$JSON["RES_ROOM_".($ind+1)."_CHILDREN_QTY"];
                $INFANTS = (int)$JSON["RES_ROOM_".($ind+1)."_INFANTS_QTY"];
                ?>
                <br>
                <div>Room Type: <? print $ROOM["NAME"] ?></div>
                <div>Rate: 
                <? 
                    //print implode(", ",$ROOM["CLASS_NAMES"]); 
                    //if (is_array($ROOM["SPECIAL_NAMES"])) print ", ".implode(", ",$ROOM["SPECIAL_NAMES"]); 
                    $CLASSES = array();
                    $SPECIALS = array();
                    foreach ($ROOM["CLASS_NAMES"] as $KEY=>$REF) if (isset($RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE])) array_push($CLASSES, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
                    print implode(", ",array_unique($CLASSES));
                    if (is_array($ROOM["SPECIAL_NAMES"])) {
                        foreach ($ROOM["SPECIAL_NAMES"] as $KEY=>$REF) array_push($SPECIALS, $RES_ITEMS[$KEY]['NAME_'.$RES_LANGUAGE]);
                        print ", ".implode(", ",array_unique($SPECIALS));
                    }
                ?>
                </div>
                <? if ($RES_ROOMS_QTY>1) { ?>
                    <div>No. of Adults: <? print $JSON["RES_ROOM_".($ind+1)."_ADULTS_QTY"] ?></div>
                    <div>No. of Children: <? print ($CHILDREN - $INFANTS) ?></div>
                    <div>No. of Infants: <? print $INFANTS ?> </div>
                <? } ?>
                <div>Daily Charges</div>
                <? 
                if (is_array($ROOM['NIGTHS'])) {
                  foreach ($ROOM['NIGTHS'] as $NIGHT => $NIGHT_DATA ) { 
                    if (is_array($NIGHT_DATA)) {
                      $GROSS = (int)$NIGHT_DATA['RATE']['GROSS'];
                      $FINAL = (int)$NIGHT_DATA['RATE']['FINAL'];
                      ?>
                      <div><? print shortDate($NIGHT) ?> - 
                      <? if ($GROSS != $FINAL) print "USD ".number_format($GROSS)." - " ?>
                      USD <? print number_format($FINAL) ?></div>
                      <? 
                    }
                  } 
                }
                ?>
                <? if ($RES_ROOMS_QTY>1) { ?>
                    <div>Room Charge: USD <? print number_format($RESERVATION['RES_ROOM_CHARGE'][$ind]) ?></div>
                <? } ?>
            <? } ?>
            <br>
            <div>Total Room Charge: USD <? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></div>
            <? if (isset($RESERVATION['SUPPLEMENT']) && trim($RESERVATION['SUPPLEMENT'])!="") {  ?>
                <div><b>Supplement: USD <? print number_format($RESERVATION['SUPPLEMENT']) ?></b></div>
                <div><b>Final Charge USD <? print number_format((int)$RESERVATION['RES_TOTAL_CHARGE'] + (int)$RESERVATION['SUPPLEMENT']) ?></b></div>
            <? } ?>
            <? if (isset($RESERVATION['FEES']) && trim($RESERVATION['FEES'])!="" && (int)$RESVIEW['STATUS']!=1) {  ?>
                <div><b>Penalty Fee: USD <? print number_format($RESERVATION['FEES']) ?></b></div>
            <? } ?>
            <? //if ($RESVIEW['STATUS_STR']=="booked") { ?>
            <div style='text-align:center;margin-top:10px' rel="<?=$RESVIEW['STATUS_STR']?>,<?=$CCPS['STATUS']?>">
                <? //if ((int)$CCPS['STATUS'] != 1) { ?>
                <a href="<? print $THIS_PAGE."&MODIFY=RESERV"; ?>"><span class="button key">Modify/Rebook</span></a>
                <? //} ?>
                <br><br>
                <a href="<? print $THIS_PAGE."&MODIFY=CANCEL"; ?>"><span class="button negative">Cancel</span></a>
                &nbsp;&nbsp;
                <a href="<? print $THIS_PAGE."&MODIFY=NOSHOW"; ?>"><span class="button negative">No Show</span></a>
                &nbsp;&nbsp;
                <? //if ((int)$CCPS['STATUS'] != 1) { ?>
                <a href="<? print $THIS_PAGE."&MODIFY=SUPPLEMENT"; ?>"><span class="button key">Supplement</span></a>
                <? //} ?>
            </div>
            <? //} ?>
            <br>
            <? 
            //if (isset($RESERVATION['TRANSFER_FEE']) && trim($RESERVATION['TRANSFER_FEE'])!="") {  
            if (isset($RESERVATION['TRANSFER_CAR']) && trim($RESERVATION['TRANSFER_CAR'])!="") {
              $tripType = $RESERVATION['TRANSFER_TYPE']=="ONEWAY"?"One Way":"Round Trip";
              $selectedCarObj = $clsTransfer->getCarById($db, array("CAR_ID"=>$RESERVATION['TRANSFER_CAR']));
              $selectedCar = "";
              while ($car = $db->fetch_array($selectedCarObj['rSet'])) $selectedCar = $car['NAME_'.$RES_LANGUAGE];
              $arrFlight = "Arrival Flight: ".$RESERVATION['AIRLINE']." ".$RESERVATION['FLIGHT']." @ ".$RESERVATION['ARRIVAL']." ".$RESERVATION['ARRIVAL_AP'];
              $depFlight = $RESERVATION['TRANSFER_TYPE']=="ONEWAY"?"":"Departure Flight: ".$RESERVATION['DEPARTURE_AIRLINE']." ".$RESERVATION['DEPARTURE_FLIGHT']." @ ".$RESERVATION['DEPARTURE']." ".$RESERVATION['DEPARTURE_AP']."<br>";
              $transferFee = number_format($RESERVATION['TRANSFER_FEE']);
              $isCancelled = $RESERVATION['TRANSFER_TYPE']=="" && $RESERVATION['TRANSFER_CAR']=="";
              if (!$isCancelled) {
                print "
                  <b>TRANSFER</b><br>
                  $tripType<br>
                  Selected Car: $selectedCar<br>
                  $arrFlight<br>
                  $depFlight
                ";
              }
              print "
                <b>Transfer Fee: USD $transferFee</b>
              ";
              if ($isCancelled) {
                print " <span class='resStatus_cancelled'>Cancelled</span><br>";
              } else { ?>
              <br>
              <div style='text-align:center;margin-top:10px'>
                <a href="<? print $THIS_PAGE."&MODIFY=TRANSFER"; ?>"><span class="button key">Modify</span></a>
                <? if ($CAN_CANCEL_TRANSFER) { ?>
                  &nbsp;&nbsp;<a href="<? print $THIS_PAGE."&MODIFY=TRANSFER_CANCEL"; ?>"><span class="button negative">Cancel</span></a>
                <? } ?>
              </div>
              <? } ?>
            <? } else { 
              $RESERVATION['TRANSFER_FEE'] = 0;
              if ($CAN_ADD_TRANSFER) { ?>
                  <br>
                  <div style='text-align:center'>
                    <a href="<? print $THIS_PAGE."&MODIFY=TRANSFER"; ?>"><span class="button key">Add Private Airport Transfer</span></a>
                    <br><br>
                  </div>
              <? } ?>
            <? } ?>
            <br>
            <div style='font-size:16px'><b>Total Charge: USD <? print number_format((int)$RESERVATION['RES_TOTAL_CHARGE'] + (isset($RESERVATION['SUPPLEMENT'])?(int)$RESERVATION['SUPPLEMENT']:0) + (int)$RESERVATION['TRANSFER_FEE']) ?></b></div>
        </div>
    </fieldset>
<? } ?>