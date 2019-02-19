<?
/*
 * Revised: Sep 15, 2011
 *          Aug 16, 2017
 */

$GUEST_LABEL = isset($RES_ROOMS_QTY) ? (($RES_ROOMS_QTY == 1) ? "Guest" : "Contact") : "Guest";
$showPWD = isset($showPWD) ? $showPWD : false;

//print "<pre>";print_r($_GET);print "</pre>";
//print "<pre>";print_r($_POST);print "</pre>";
//print "<pre>";print_r($GUEST);print "</pre>";
//print "ARRAY:<pre>";print_r($JSON);print "</pre>";

$RES_GUEST_EMAIL = isset($_POST['RES_GUEST_EMAIL'])?$_POST['RES_GUEST_EMAIL']:"";
$RES_GUEST_EMAIL = isset($GUEST['EMAIL'])?$GUEST['EMAIL']:$RES_GUEST_EMAIL;

?>
<fieldset>
    <legend>Guest Contact Information</legend>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Salutation</td>
            <td width="100%" valign="top"><select id="RES_GUEST_TITLE" name="RES_GUEST_TITLE"><? include "m.global.title.php" ?></select></td>
        </tr>
        <tr>
            <td valign="top" nowrap><? print $GUEST_LABEL ?> First Name</td>
            <td width="100%" valign="top"><input type="text" name="RES_GUEST_FIRSTNAME" id="RES_GUEST_FIRSTNAME" class="large" value="<? print isset($GUEST['FIRSTNAME'])?$GUEST['FIRSTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap><? print $GUEST_LABEL ?> Last Name</td>
            <td width="100%" valign="top"><input type="text" name="RES_GUEST_LASTNAME" id="RES_GUEST_LASTNAME" class="large" value="<? print isset($GUEST['LASTNAME'])?$GUEST['LASTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap><? print $GUEST_LABEL ?> Email</td>
            <td width="100%" valign="top"><input type="text" name="RES_GUEST_EMAIL" id="RES_GUEST_EMAIL" class="large" value="<? print $RES_GUEST_EMAIL ?>"></td>
        </tr>
        <? if ($showPWD) { ?>
        <tr>
            <td valign="top" nowrap><? print $GUEST_LABEL ?> Password</td>
            <td width="100%" valign="top">
                <input type="text" name="RES_GUEST_PASSWORD" id="RES_GUEST_PASSWORD" class="large" value="<? print isset($GUEST['PASSWORD'])?$GUEST['PASSWORD']:"" ?>">
                &nbsp;<a href="javascript:void(0)" onClick="ibe.callcenter.sendGuestPwd('<? print isset($GUEST['EMAIL'])?$GUEST['EMAIL']:"" ?>')">Send Password in E-Mail</a>
            </td>
        </tr>
        <? } ?>
        <tr style="display:none">
            <td valign="top" nowrap>Email Language</td>
            <td width="100%" valign="top">
                <select id="RES_GUEST_LANGUAGE" name="RES_GUEST_LANGUAGE">
                    <option value="EN">English</option>
                    <option value="SP">Spanish</option>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap>Mailing List</td>
            <td width="100%" valign="top">
                <select id="MAILING_LIST" name="RES_GUEST_MAILING_LIST">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </td>
        </tr>
        </table>
    </div>
</fieldset>

<fieldset>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Address</td>
            <td width="100%" valign="top"><input type="text" id="RES_GUEST_ADDRESS" class="large" title="Address" name="RES_GUEST_ADDRESS" value="<? print isset($GUEST['ADDRESS'])?$GUEST['ADDRESS']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Country</td>
            <td width="100%" valign="top"><? print $clsGlobal->getCountriesDropDown($db, array("ELE_ID"=>"RES_GUEST_COUNTRY")); ?></td>
        </tr>
        <tr>
            <td valign="top" nowrap>State/Province</td>
            <td width="100%" valign="top" style='display:none' id="GUEST_STATES">
                <input type="text" id="RES_GUEST_STATE" class="med" title="State/Province" name="RES_GUEST_STATE" value="<? print isset($GUEST['STATE'])?$GUEST['STATE']:"" ?>">
                <? 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"US_GUEST_STATES","CODE"=>"US")); 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"CA_GUEST_STATES","CODE"=>"CA")); 
                    print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"MX_GUEST_STATES","CODE"=>"MX")); 
                ?>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap>City</td>
            <td width="100%" valign="top"><input type="text" id="RES_GUEST_CITY" class="med" title="City" name="RES_GUEST_CITY" value="<? print isset($GUEST['CITY'])?$GUEST['CITY']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Zip/Postal code</td>
            <td width="100%" valign="top"><input type="text" id="RES_GUEST_ZIPCODE" class="med" title="Zip/Postal code" name="RES_GUEST_ZIPCODE" value="<? print isset($GUEST['ZIPCODE'])?$GUEST['ZIPCODE']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Phone Number</td>
            <td width="100%" valign="top"><input type="text" id="RES_GUEST_PHONE" class="large" title="Phone" name="RES_GUEST_PHONE" value="<? print isset($GUEST['PHONE'])?$GUEST['PHONE']:"" ?>"></td>
        </tr>
        </table>
    </div>
</fieldset>

<? 
if ($RESERVATION['FORWHOM']['RES_TO_WHOM']=="GUEST" && (int)$RESERVATION['FORWHOM']['RES_TA_ID']==0 && $_GET["PAGE_CODE"]=="edit_reserv") { 
//if (isset($RESERVATION) && $RESERVATION['FORWHOM']['RES_TO_WHOM']=="GUEST" && $_GET["PAGE_CODE"]=="edit_reserv") { 
//if (isset($RESERVATION) && $_GET["PAGE_CODE"]=="edit_reserv") { 
    $assign_ta = true;
    $is_Rebooking = false;
    include "m.reserv.forwhom.ta.php";
} else {
  print $RESERVATION['FORWHOM']['RES_TO_WHOM']." - ";
  print $RESERVATION['FORWHOM']['RES_TA_ID'];
}
?>



<script>
<? include "m.reserv.room.guest.scripts.php"; ?>
</script>
