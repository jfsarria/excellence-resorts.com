<?
/*
 * Revised: Mar 09, 2012
 */

$isEDIT = isset($isEDIT) ? $isEDIT : false;
$WAS_SUBMITTED = false;
if (isset($_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT'])) {
    extract($_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']);
    $WAS_SUBMITTED = true;
}
//if (isset($RESERVATION['PAYMENT'])) print "<pre>";print_r($RESERVATION['PAYMENT']);print "</pre>";
?>

<fieldset>
    <legend>Payment Information</legend>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Total Reservation Charge:</td>
            <td width="100%" valign="top" style='font-size:14px;padding-bottom:3px'><b>$<? print number_format($RESERVATION['RES_TOTAL_CHARGE']) ?></b></td>
        </tr>
        <tr>
            <td valign="top" nowrap>
            <? if ($RESERVATION['FORWHOM']['RES_TO_WHOM'] == "TA") { ?>
                <table>
                <tr>
                    <td nowrap>Wire Transfer</td>
                    <td><input type="radio" name="RES_GUESTMETHOD" id="RES_GUESTMETHOD_WIRE" value="WIRE" onclick="ibe.reserv.rooms.paymentMethod(this)"></td>
                </tr>
                <tr>
                    <td nowrap>Credit Card</td>
                    <td><input type="radio" name="RES_GUESTMETHOD" id="RES_GUESTMETHOD_CC" value="CC" checked onclick="ibe.reserv.rooms.paymentMethod(this)"></td>
                </tr>
                </table>
            <? } else { ?>
                <input type="hidden" name="RES_GUESTMETHOD" id="RES_GUESTMETHOD" value="CC">
            <? } ?>
            </td>
        </tr>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Credit Card Type</td>
            <td width="100%" valign="top">
                <select id="RES_CC_TYPE" name="RES_CC_TYPE">
                    <option value="Visa">Visa</option>
                    <option value="MasterCard">MasterCard</option>
                    <option value="AmEx">American Express</option>
                </select>            
            </td>
        </tr>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Credit Card Number</td>
            <td width="100%" valign="top"><input type="text" name="RES_CC_NUMBER" id="RES_CC_NUMBER" class="large"></td>
        </tr>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Name on Credit Card</td>
            <td width="100%" valign="top"><input type="text" name="RES_CC_NAME" id="RES_CC_NAME" class="large" value="<? print isset($CC_NAME) ? $CC_NAME : "" ?>"></td>
        </tr>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Security Code</td>
            <td width="100%" valign="top"><input type="text" name="RES_CC_CODE" id="RES_CC_CODE" class="small" value="<? print isset($CC_CODE) ? $CC_CODE : "" ?>"></td>
        </tr>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Expiration Date</td>
            <td width="100%" valign="top">
                <? print $clsGlobal->getExpMonthsDropDown(array()); ?>
            </td>
        </tr>
        <? if (!$isEDIT) if (!$WAS_SUBMITTED ) { ?>
        <tr class='paymentMethod'>
            <td valign="top" nowrap>Billing address<BR>same as above?</td>
            <td width="100%" valign="top"><input type="checkbox" name="RES_CC_SAME_ADDRESS" id="RES_CC_SAME_ADDRESS" onclick="ibe.reserv.rooms.sameBilling(this.checked)"></td>
        </tr>
        <? } ?>
        </table>
    </div>
</fieldset>

<fieldset id="res_billing_info" class='paymentMethod'>
    <legend>Billing Information</legend>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Address</td>
            <td width="100%" valign="top"><input type="text" id="RES_CC_BILL_ADDRESS" class="large" title="Address" name="RES_CC_BILL_ADDRESS" value="<? print isset($CC_BILL_ADDRESS) ? $CC_BILL_ADDRESS : "" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>City</td>
            <td width="100%" valign="top"><input type="text" id="RES_CC_BILL_CITY" class="med" title="City" name="RES_CC_BILL_CITY" value="<? print isset($CC_BILL_CITY) ? $CC_BILL_CITY : "" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Country</td>
            <td width="100%" valign="top"><? print $clsGlobal->getCountriesDropDown($db, array("ELE_ID"=>"RES_CC_BILL_COUNTRY")); ?></td>
        </tr>
        <tr>
            <td valign="top" nowrap>State/Province</td>
            <td width="100%" valign="top" style='display:none' id="PAYMENT_STATES">
                <input type="text" id="RES_CC_BILL_STATE" class="med" title="State/Province" name="RES_CC_BILL_STATE" value="<? print isset($CC_BILL_STATE) ? $CC_BILL_STATE : "" ?>">
                <? 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"US_PAYMENT_STATES","CODE"=>"US")); 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"CA_PAYMENT_STATES","CODE"=>"CA")); 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"MX_PAYMENT_STATES","CODE"=>"MX")); 
                ?>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap>Zip/Postal code</td>
            <td width="100%" valign="top"><input type="text" id="RES_CC_BILL_ZIPCODE" class="med" title="Zip/Postal code" name="RES_CC_BILL_ZIPCODE" value="<? print isset($CC_BILL_ZIPCODE) ? $CC_BILL_ZIPCODE : "" ?>"></td>
        </tr>
        </table>
    </div>
</fieldset>

<fieldset class='paymentMethod'>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Payment confirmation<br>email, if different than<br><? print $RESERVATION['FORWHOM']['RES_TO_WHOM'] ?> email</td>
            <td width="100%" valign="top"><input type="text" id="RES_CC_BILL_EMAIL" class="large" title="Phone" name="RES_CC_BILL_EMAIL" value="<? print isset($CC_BILL_EMAIL) ? $CC_BILL_EMAIL : "" ?>"></td>
        </tr>
        </table>
    </div>
</fieldset>

<? if ($WAS_SUBMITTED) { ?>
    <div style='text-align:right'>
        <span class="button" onclick="if (ibe.page.validateCC()) $('#reservForm').submit();">Continue &#187;</span>
    </div>
<? } ?>


<script>
<? if ($WAS_SUBMITTED || $isEDIT) { ?>
    $("#RES_CC_TYPE").val('<? print $CC_TYPE ?>');
    $("#RES_CC_BILL_COUNTRY").val('<? print $CC_BILL_COUNTRY ?>');
    var CC_EXP = '<? print $CC_EXP ?>'.split("/");
    $("#card-exp-MM").val(CC_EXP[0]);
    $("#card-exp-YY").val(CC_EXP[1]);
<? } ?>

<? include "m.reserv.room.payment.scripts.php"; ?>

</script>