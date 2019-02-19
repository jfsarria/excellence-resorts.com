<?
/*
 * Revised: Jul 25, 2011
 *          Dec 10, 2014
 */

$TRANSFER_NOTES = "";
if (isset($RESERVATION['TRANSFER_FEE'])&&(int)$RESERVATION['TRANSFER_FEE']!=0) {
    $TRANSFER_NOTES = " - ".($RESERVATION['TRANSFER_TYPE']=="ONEWAY"?"One Way":"Round Trip")." Transfer for USD ".number_format($RESERVATION['TRANSFER_FEE'])." cancelled with this reservation on ".date("M j, Y");
}
?>
<fieldset>
    <legend><? print $MODIFY=="CANCEL"?"Cancel":"No Show" ?> Reservation</legend>
    <div class="fieldset">
        <div class="field">Fees $<input id="FEES" name="FEES" value="<? print isset($FEES) ? $FEES : "" ?>" style="width:70px"></div>
    </div>
    <div class="fieldset">
        <div class="label">Notes</div>
        <div class="field"><textarea id="NOTES" name="NOTES" class="full"><? print isset($RESERVATION['NOTES']) ? $RESERVATION['NOTES'] : "" ?><?=$TRANSFER_NOTES?></textarea></div>
    </div>
</fieldset>