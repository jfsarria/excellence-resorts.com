<?
/*
 * Revised: Nov 08, 2011
 *          Feb 11, 2014
 *          Oct 01, 2014
 */

$isEDIT = isset($isEDIT) ? $isEDIT : false;

global $_TODAY, $_TRANSFER_DAYS, $_PICKUP_DAYS;;
$DAYS_LEFT = dateDiff($_TODAY, $RES_CHECK_IN, "D", false);
$IS_TRANSFER_ACTIVE = $clsTransfer->isActive($db, array("PROP_ID"=>$RES_PROP_ID));
$CAN_BOOK_TRANSFER = $DAYS_LEFT >= $_TRANSFER_DAYS + ($isEDIT?1:0) && $IS_TRANSFER_ACTIVE==1;
$CAN_BOOK_PICKUP = $DAYS_LEFT >= $_PICKUP_DAYS;

?>
<fieldset>
    <legend>Optional information</legend>
    <div class="fieldset">
    <? 
    $ROOM_NUM = 0;
    foreach ($RESERVATION['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
        //$ROOM = $RES_ITEMS[$ROOM_ID]; 
        $STYLE = ($RES_ROOMS_QTY == 1) ? "style='display:none'" : "";
        $ROOM = (isset($RESERVATION['ROOMS']) && isset($RESERVATION['ROOMS'][$ROOM_NUM])) ? $RESERVATION['ROOMS'][$ROOM_NUM] : array();
        ?>
        <table width="100%" cellspacing="4" cellpadding="1">
            <? if ($RES_ROOMS_QTY > 1) { ?>
            <tr class='res_row_header'>
                <td valign="top" colspan="10">Room <? print ($ROOM_NUM+1).": ".$RES_ITEMS[$ROOM_ID]['NAME_'.$RES_LANGUAGE] ?></td>
            </tr>
            <? } ?>
            <? if (!$isEDIT) { ?>
            <tr <? print $STYLE ?>>
                <td valign="top" nowrap>Same as contact<br>information</td>
                <td width="100%" valign="top"><span><input type="checkbox" id="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_SAMEASGUEST" onclick="ibe.reserv.rooms.sameContact(this.checked,'<? print $ROOM_KEY ?>')"></span></td>
            </tr>
            <? } ?>
            <tr <? print $STYLE ?>>
                <td valign="top" nowrap>Guest Title</td>
                <td width="100%" valign="top"><select id="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_TITLE" name="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_TITLE"><? include "m.global.title.php" ?></select></td>
            </tr>
            <tr <? print $STYLE ?>>
                <td valign="top" nowrap>Guest First Name</td>
                <td width="100%" valign="top"><input type="text" name="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_FIRSTNAME" id="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_FIRSTNAME" class="large" value="<? if (isset($ROOM['GUEST_FIRSTNAME'])) print $ROOM['GUEST_FIRSTNAME'] ?>"></td>
            </tr>
            <tr <? print $STYLE ?>>
                <td valign="top" nowrap>Guest Last Name</td>
                <td width="100%" valign="top"><input type="text" name="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_LASTNAME" id="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_LASTNAME" class="large" value="<? if (isset($ROOM['GUEST_LASTNAME'])) print $ROOM['GUEST_LASTNAME'] ?>"></td>
            </tr>

            <tr>
                <td valign="top" nowrap>Repeat Guest</td>
                <td width="100%" valign="top"><? print $clsUsers->propertiesCheckBoxes($db, array("ELE_ID"=>"RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED","PID"=>$RES_PROP_ID)) ?></td>
            </tr>
            <tr>
                <td valign="top" nowrap>Bed Type preference</td>
                <td width="100%" valign="top"><? print (isset($RES_ITEMS[$ROOM_ID])) ? $clsGlobal->getBedTypesDropDown($db, array("ELE_ID"=>"RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE","BEDS"=>$RES_ITEMS[$ROOM_ID]['BEDS'],"BED_TYPES"=>$RES_ITEMS['PROPERTY']['BED_TYPES'])) : $clsRooms->getBedTypesDropDown($db, array("ELE_ID"=>"RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE","PROP_ID"=>$RES_PROP_ID)) ?></td>
            </tr>
            <tr>
                <td valign="top" nowrap>Baby Crib</td>
                <td width="100%" valign="top"><span><input type="checkbox" value="1" name="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_BABYCRIB" id="RES_GUEST_ROOM_<? print $ROOM_KEY ?>_BABYCRIB" <? if (isset($ROOM['GUEST_BABYCRIB']) && (int)$ROOM['GUEST_BABYCRIB']==1) print "checked" ?>></span></td>
            </tr>
            <tr>
                <td valign="top" nowrap>Smoking Preference</td>
                <td width="100%" valign="top"><? print $clsGlobal->getSmokingPrefeDropDown($db, array("ELE_ID"=>"RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING")) ?></td>
            </tr>
            <tr>
                <td valign="top" nowrap>Special Occasion</td>
                <td width="100%" valign="top"><? print $clsGlobal->getSpecialOccasionDropDown($db, array("ELE_ID"=>"RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION")) ?></td>
            </tr>
        </table>
        <? 
        if ($isEDIT) {
            print "<script>\n";
            if (isset($ROOM['GUEST_TITLE'])) print "$('#RES_GUEST_ROOM_{$ROOM_KEY}_TITLE').val('{$ROOM['GUEST_TITLE']}');\n";
            if (isset($ROOM['GUEST_REPEATED']) && is_array($ROOM['GUEST_REPEATED'])) {
                foreach ($ROOM['GUEST_REPEATED'] as $ind=>$PID) print "$('#RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED_{$PID}').attr('checked', true);\n";
            }
            if (isset($ROOM['GUEST_BEDTYPE'])) print "$('#RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE').val('{$ROOM['GUEST_BEDTYPE']}');\n";
            if (isset($ROOM['GUEST_SMOKING'])) print "$('#RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING').val('{$ROOM['GUEST_SMOKING']}');\n";
            if (isset($ROOM['GUEST_OCCASION'])) print "$('#RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION').val('{$ROOM['GUEST_OCCASION']}');\n";
            print "</script>\n";
        }
        ++$ROOM_NUM;
    } ?>
    </div>
</fieldset>

<?php
/*
  print " == DAYS LEFT : $DAYS_LEFT -- ";
  print $CAN_BOOK_TRANSFER ? " -- CAN TRANSFER -- " : " -- NO TRANSFER -- ";
  print $CAN_BOOK_PICKUP ? " -- CAN PICKUP -- " : " -- NO PICKUP -- ";
  print $isEDIT ? " -- IS EDIT -- " : " -- NO EDIT -- ";
*/
?>

<fieldset>
    <legend>Hotel Arrival <?php if (($CAN_BOOK_TRANSFER || $CAN_BOOK_PICKUP) && !$isEDIT) print "& Private Transfer Booking" ?></legend>
    <div class="fieldset">
        <div id="HOTEL_ARRIVAL_TBL">
          <table width="100%" cellspacing="4" cellpadding="1">
          <tr>
              <td valign="top" nowrap>Expected hotel arrival time</td>
              <td valign="top"><input type="text" name="RES_GUEST_ARRIVAL_TIME" id="RES_GUEST_ARRIVAL_TIME" style="width:40px" value="<? if (isset($RESERVATION['ARRIVAL_TIME'])) print $RESERVATION['ARRIVAL_TIME'] ?>"></td>
              <td width="100%" valign="top">
                  <span><input type="radio" value="AM" id="RES_GUEST_ARRIVAL_AM" name="RES_GUEST_ARRIVAL_AMPM" checked></span>&nbsp;AM&nbsp;
                  <span><input type="radio" value="PM" id="RES_GUEST_ARRIVAL_PM" name="RES_GUEST_ARRIVAL_AMPM" <? if (isset($RESERVATION['ARRIVAL_AMPM']) && $RESERVATION['ARRIVAL_AMPM']=="PM") print "checked" ?>></span>&nbsp;PM
              </td>
          </tr>
          <tr><td colspan='10'><hr></td></tr>
          </table>
        </div>

        <? if ($CAN_BOOK_PICKUP && (!$isEDIT || !$CAN_BOOK_TRANSFER))  { ?>
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Airport Pickup</td>
            <td valign="top"><span><input type="checkbox" name="RES_GUEST_AIRPORT_PICKUP" id="RES_GUEST_AIRPORT_PICKUP" onclick="ibe.reserv.rooms.airportPickup(this.checked, '<?=$isEDIT?1:0?>')"></span></td>
            <td width="100%" valign="top">Additional fee will apply. Land transfers must be requested at least 48 hours prior to arrival. <div style='display:none'><?="DAYS_LEFT: ".$DAYS_LEFT?></div></td>
        </tr>
        <tr class='airportPickup'>
          <td colspan="3">

          </td>
        </tr>
        </table>
        <? } ?>

        <table class="airportPickup addPrivateTransfer" width="100%" cellspacing="4" cellpadding="1">
        <? if ($CAN_BOOK_TRANSFER && !$isEDIT) { ?>
        <tr>
            <td valign="top" nowrap>
              <!-- BRAND NEW RESERVATION -->
              <h3>Add Private Airport Transfer (2)</h3>
              <!-- <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value)" type="radio" value="" name="RES_GUEST_TRANSFER_TYPE" checked></span>&nbsp;No Transfer</div> -->
              <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value, '<?=$RES_PROP_ID?>', '<?=$_SESSION['AVAILABILITY']['RES_CHECK_IN']?>', '<?=(int)$_SESSION['AVAILABILITY']['RES_ROOMS_ADULTS_QTY']+(int)$_SESSION['AVAILABILITY']['RES_ROOMS_CHILDREN_QTY']?>')" type="radio" value="ROUNDT" id="ROUNDT" name="RES_GUEST_TRANSFER_TYPE" <? if (isset($RESERVATION['TRANSFER'])&&$RESERVATION['TRANSFER']=="ROUNDT") print "checked" ?>></span>&nbsp;Round Trip</div>
              <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value, '<?=$RES_PROP_ID?>', '<?=$_SESSION['AVAILABILITY']['RES_CHECK_IN']?>', '<?=(int)$_SESSION['AVAILABILITY']['RES_ROOMS_ADULTS_QTY']+(int)$_SESSION['AVAILABILITY']['RES_ROOMS_CHILDREN_QTY']?>')" type="radio" value="ONEWAY" id="ONEWAY" name="RES_GUEST_TRANSFER_TYPE" <? if (isset($RESERVATION['TRANSFER'])&&$RESERVATION['TRANSFER']=="ONEWAY") print "checked" ?>></span>&nbsp;One Way</div>
            </td>
        </tr>
        <? } ?>

        <? if ($CAN_BOOK_PICKUP && (!$isEDIT || !$CAN_BOOK_TRANSFER))  { ?>
        <tr>
            <td valign="top">
              <table>
              <tr>
                <td>Arrival Airline</td>
                <td>Arrival Flight</td>
                <td colspan="2">Flight Arrival Time</td>
              </tr>
              <tr>
                <td><input type="text" name="RES_GUEST_AIRLINE" id="RES_GUEST_AIRLINE" class="med" value="<? if (isset($RESERVATION['AIRLINE'])) print $RESERVATION['AIRLINE'] ?>"></td>
                <td><input type="text" name="RES_GUEST_FLIGHT" id="RES_GUEST_FLIGHT" class="med" value="<? if (isset($RESERVATION['FLIGHT'])) print $RESERVATION['FLIGHT'] ?>"></td>
                <td><input type="text" name="RES_GUEST_ARRIVAL" id="RES_GUEST_ARRIVAL" style="width:40px" value="<? if (isset($RESERVATION['ARRIVAL'])) print $RESERVATION['ARRIVAL'] ?>"></td>
                <td>
                  <span><input type="radio" value="AM" id="RES_GUEST_A_AM" name="RES_GUEST_ARRIVAL_AP" checked></span>&nbsp;AM&nbsp;
                  <span><input type="radio" value="PM" id="RES_GUEST_A_PM" name="RES_GUEST_ARRIVAL_AP" <? if (isset($RESERVATION['ARRIVAL_AP']) && $RESERVATION['ARRIVAL_AP']=="PM") print "checked" ?>></span>&nbsp;PM                
                </td>
              </tr>
              </table>
            </td>
        </tr>
        <? } ?>
        <? if ($CAN_BOOK_TRANSFER && !$isEDIT) { ?>
        <tr>
            <td valign="top">
              <table id="DEPARTURE_INFO_TBL">
              <tr>
                <td>Departure Airline</td>
                <td>Departure Flight</td>
                <td colspan="2">Flight Departure Time</td>
              </tr>
              <tr>
                <td><input type="text" name="RES_GUEST_DEPARTURE_AIRLINE" id="RES_GUEST_DEPARTURE_AIRLINE" class="med" value="<? if (isset($RESERVATION['DEPARTURE_AIRLINE'])) print $RESERVATION['DEPARTURE_AIRLINE'] ?>"></td>
                <td><input type="text" name="RES_GUEST_DEPARTURE_FLIGHT" id="RES_GUEST_DEPARTURE_FLIGHT" class="med" value="<? if (isset($RESERVATION['DEPARTURE_FLIGHT'])) print $RESERVATION['DEPARTURE_FLIGHT'] ?>"></td>
                <td><input type="text" name="RES_GUEST_DEPARTURE" id="RES_GUEST_DEPARTURE" style="width:40px" value="<? if (isset($RESERVATION['DEPARTURE'])) print $RESERVATION['DEPARTURE'] ?>"></td>
                <td>
                  <span><input type="radio" value="AM" id="RES_GUEST_D_AM" name="RES_GUEST_DEPARTURE_AP" checked></span>&nbsp;AM&nbsp;
                  <span><input type="radio" value="PM" id="RES_GUEST_D_PM" name="RES_GUEST_DEPARTURE_AP" <? if (isset($RESERVATION['DEPARTURE_AP']) && $RESERVATION['DEPARTURE_AP']=="PM") print "checked" ?>></span>&nbsp;PM                
                </td>
              </tr>
              </table>
              <div id="TRANSFER_CARS_LIST"></div>
            </td>
        </tr>
        <? } ?>
        </table>

        <? 
        if ($isEDIT) {
            print "
            <script>
                if ($('#RES_GUEST_AIRLINE').val()!='' || $('#RES_GUEST_FLIGHT').val()!='') {
                    $('#RES_GUEST_AIRPORT_PICKUP').attr('checked', true);
                    ibe.reserv.rooms.airportPickup(true);
                }
            </script>
            ";
        }
        ?>
    </div>
</fieldset>

<?
//print "<pre>";print_r($_SESSION['AVAILABILITY']);print "</pre>";
?>
<? if (!$isEDIT) { ?>

<? } ?>
