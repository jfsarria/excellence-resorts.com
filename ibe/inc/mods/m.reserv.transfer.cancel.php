<?
/*
 * Revised: Feb 11, 2014
 *          Dec 10, 2014
 */

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
      <div class="label" style='font-size:16px'>Total Transfer Charge: <b>USD $<span id='total_transfer_charge'><?=number_format($RESERVATION['TRANSFER_FEE'])?></span></b></div>
  </div>
  <br>

  <input type="hidden" name="SUPPLEMENT" value="<? print isset($RESERVATION['SUPPLEMENT']) ? $RESERVATION['SUPPLEMENT'] : "" ?>">
  <input type="hidden" name="RES_GUEST_TRANSFER_TYPE" value="CANCELLED">
  <input type="hidden" name="RES_GUEST_TRANSFER_CAR" value="">
  <input type="hidden" name="RES_GUEST_TRANSFER_FEE" value="<?=(int)$CCPS['STATUS']==0?"":$RESERVATION['TRANSFER_FEE']?>">
  <input type="hidden" name="ALREADY_CHARGED" value="<?=(int)$CCPS['STATUS']==0?"0":"1"?>">

  <input type="hidden" name="RES_GUEST_AIRLINE" value="">
  <input type="hidden" name="RES_GUEST_FLIGHT" value="">
  <input type="hidden" name="RES_GUEST_ARRIVAL" value="">
  <input type="hidden" name="RES_GUEST_DEPARTURE_AIRLINE" value="">
  <input type="hidden" name="RES_GUEST_DEPARTURE_FLIGHT" value="">
  <input type="hidden" name="RES_GUEST_DEPARTURE" value="">

  <div class="fieldset">
      <div class="label">Cancelation Comments</div>
      <div class="field"><textarea name="CC_COMMENTS" class="full"><? print isset($RESERVATION['CC_COMMENTS']) ? $RESERVATION['CC_COMMENTS'] : "" ?> - <?=($RESERVATION['TRANSFER_FEE']=="ONEWAY"?"One Way":"Round Trip")?> Transfer for USD <?=number_format($RESERVATION['TRANSFER_FEE'])?> cancelled on <?=date("M j, Y")?></textarea></div>
  </div>
</fieldset>