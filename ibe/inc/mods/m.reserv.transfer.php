<?
/*
 * Revised: Feb 11, 2014
 */

$RESERVATION['AIRLINE'] = isset($RESERVATION['AIRLINE']) ? $RESERVATION['AIRLINE'] : "";
$RESERVATION['FLIGHT'] = isset($RESERVATION['FLIGHT']) ? $RESERVATION['FLIGHT'] : "";
$RESERVATION['ARRIVAL'] = isset($RESERVATION['ARRIVAL']) ? $RESERVATION['ARRIVAL'] : "";
$RESERVATION['ARRIVAL_AP'] = isset($RESERVATION['ARRIVAL_AP']) ? $RESERVATION['ARRIVAL_AP'] : "";
$RESERVATION['DEPARTURE_AIRLINE'] = isset($RESERVATION['DEPARTURE_AIRLINE']) ? $RESERVATION['DEPARTURE_AIRLINE'] : "";
$RESERVATION['DEPARTURE_FLIGHT'] = isset($RESERVATION['DEPARTURE_FLIGHT']) ? $RESERVATION['DEPARTURE_FLIGHT'] : "";
$RESERVATION['DEPARTURE'] = isset($RESERVATION['DEPARTURE']) ? $RESERVATION['DEPARTURE'] : "";
$RESERVATION['DEPARTURE_AP'] = isset($RESERVATION['DEPARTURE_AP']) ? $RESERVATION['DEPARTURE_AP'] : "";
$RESERVATION['TRANSFER_TYPE'] = isset($RESERVATION['TRANSFER_TYPE']) ? $RESERVATION['TRANSFER_TYPE'] : "";
$RESERVATION['TRANSFER_CAR'] = isset($RESERVATION['TRANSFER_CAR']) ? $RESERVATION['TRANSFER_CAR'] : 0;
$RESERVATION['TRANSFER_FEE'] = isset($RESERVATION['TRANSFER_FEE']) ? $RESERVATION['TRANSFER_FEE'] : 0;

?>
<fieldset>
  <legend>Reservation Info</legend>
  <div class="fieldset">
      <div>Hotel: <? print $RES_ITEMS['PROPERTY']['NAME'] ?></div>
      <div>No. of Rooms: <? print $RES_ROOMS_QTY ?></div>
      <div>No. of Guests: <? print $RES_ROOMS_ADULTS_QTY ?></div>
      <div>Book Date: <? print shortDate($RESVIEW['CREATED']) ?></div>
      <div>CheckIn: <? print shortDate($RES_CHECK_IN) ?></div>
      <div>CheckOut: <? print shortDate($RES_CHECK_OUT) ?></div>
  </div>
</fieldset>

<fieldset>
  <legend>Private Transfer Booking</legend>
  <div class="fieldset">
        <table class="airportPickup addPrivateTransfer" width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>
              <?
                // EXISTING RESERVATIONS
                $ALREADY_HAS_TRANSFER = (int)$RESERVATION['TRANSFER_FEE']==0 ? false : true;
              ?>
              <h3><? print $ALREADY_HAS_TRANSFER ? "Add" : "Modify" ?> Private Airport Transfer (0)</h3>
              <? if ($CAN_ADD_TRANSFER) {?>
                  <input type="hidden" id="SUPPLEMENT" name="SUPPLEMENT" value="<? print isset($RESERVATION['SUPPLEMENT']) ? $RESERVATION['SUPPLEMENT'] : "" ?>">
                  <!-- <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value)" type="radio" value="" name="RES_GUEST_TRANSFER_TYPE" checked></span>&nbsp;No Transfer</div> -->
                  <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value, '<?=$RES_PROP_ID?>', '<?=$RES_CHECK_IN?>', '<?=(int)$RES_ROOMS_ADULTS_QTY+(int)$RES_ROOMS_CHILDREN_QTY?>')" type="radio" id="ROUNDT" value="ROUNDT" name="RES_GUEST_TRANSFER_TYPE" <? if (isset($RESERVATION['TRANSFER'])&&$RESERVATION['TRANSFER']=="ROUNDT") print "checked" ?>></span>&nbsp;Round Trip</div>
                  <div><span><input onclick="ibe.reserv.rooms.addTranfers(this.value, '<?=$RES_PROP_ID?>', '<?=$RES_CHECK_OUT?>', '<?=(int)$RES_ROOMS_ADULTS_QTY+(int)$RES_ROOMS_CHILDREN_QTY?>')" type="radio" id="ONEWAY" value="ONEWAY" name="RES_GUEST_TRANSFER_TYPE" <? if (isset($RESERVATION['TRANSFER'])&&$RESERVATION['TRANSFER']=="ONEWAY") print "checked" ?>></span>&nbsp;One Way</div>
              <? } ?>
            </td>
        </tr>
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
              <? if ($CAN_ADD_TRANSFER) {?>
                <div id="TRANSFER_CARS_LIST"></div>
              <? } ?>
            </td>
        </tr>
        </table>
  </div>
  <div class="fieldset">
      <div class="label" style='font-size:16px'>Total Transfer Charge: <b>USD $<span id='total_transfer_charge'><?=(int)$RESERVATION['TRANSFER_FEE']?></span></b></div>
  </div>
  <br>
  <div class="fieldset">
      <div class="label">Comments</div>
      <div class="field"><textarea id="CC_COMMENTS" name="CC_COMMENTS" class="full"><? print isset($RESERVATION['CC_COMMENTS']) ? $RESERVATION['CC_COMMENTS'] : "" ?></textarea></div>
  </div>
</fieldset>

<script>
  $(".addPrivateTransfer").css("display","block");

  var CAN_BOOK_TRANSFER = <?= $CAN_ADD_TRANSFER ? 1 : 0 ?>,
      TRANSFER_FEE = parseInt('<?=$RESERVATION['TRANSFER_FEE']?>',10),
      TRANSFER_CAR = parseInt('<?=$RESERVATION['TRANSFER_CAR']?>',10),
      TRANSFER_TYPE = '<?=$RESERVATION['TRANSFER_TYPE']?>',
      total_transfer_charge = $("#total_transfer_charge");
  
  if (CAN_BOOK_TRANSFER==0) {
    if (TRANSFER_TYPE=="ROUNDT")	{
      $("#DEPARTURE_INFO_TBL").show();
    } else {
      $("#DEPARTURE_INFO_TBL").hide();
    }
	  if (total_transfer_charge.length==1) total_transfer_charge.html(ibe.page.formatCurrency(TRANSFER_FEE));
  } else {
    if (TRANSFER_FEE!="" && TRANSFER_TYPE!="" && TRANSFER_CAR!="") {
      $("#"+TRANSFER_TYPE).click();
    } else {
      $("#ROUNDT").click();
    }
  }

</script>