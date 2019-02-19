<?
/*
 * Revised: Sep 07, 2011
 */

?>
<fieldset>
    <legend>Guest Contact Information</legend>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Salutation</td>
            <td width="100%" valign="top"><select id="TITLE" name="TITLE"><? include "m.global.title.php" ?></select></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Guest First Name</td>
            <td width="100%" valign="top"><input type="text" name="FIRSTNAME" id="FIRSTNAME" class="large" value="<? print isset($GUEST['FIRSTNAME'])?$GUEST['FIRSTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Guest Last Name</td>
            <td width="100%" valign="top"><input type="text" name="LASTNAME" id="LASTNAME" class="large" value="<? print isset($GUEST['LASTNAME'])?$GUEST['LASTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Guest Email</td>
            <td width="100%" valign="top"><input type="text" name="EMAIL" id="EMAIL" class="large" value="<? print isset($GUEST['EMAIL'])?$GUEST['EMAIL']:"" ?>"></td>
        </tr>
        </table>
    </div>
</fieldset>

<fieldset>
    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>Address</td>
            <td width="100%" valign="top"><input type="text" id="ADDRESS" class="large" title="Address" name="ADDRESS" value="<? print isset($GUEST['ADDRESS'])?$GUEST['ADDRESS']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Country</td>
            <td width="100%" valign="top"><input type="text" id="COUNTRY" class="large" title="Country" name="COUNTRY" value="<? print isset($GUEST['COUNTRY'])?$GUEST['COUNTRY']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>State/Province</td>
            <td width="100%" valign="top"><input type="text" id="STATE" class="med" title="State/Province" name="STATE" value="<? print isset($GUEST['STATE'])?$GUEST['STATE']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>City</td>
            <td width="100%" valign="top"><input type="text" id="CITY" class="med" title="City" name="CITY" value="<? print isset($GUEST['CITY'])?$GUEST['CITY']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Zip/Postal code</td>
            <td width="100%" valign="top"><input type="text" id="ZIPCODE" class="med" title="Zip/Postal code" name="ZIPCODE" value="<? print isset($GUEST['ZIPCODE'])?$GUEST['ZIPCODE']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Phone Number</td>
            <td width="100%" valign="top"><input type="text" id="PHONE" class="large" title="Phone" name="PHONE" value="<? print isset($GUEST['PHONE'])?$GUEST['PHONE']:"" ?>"></td>
        </tr>
        </table>
    </div>
</fieldset>


<script>
    //$("INPUT, SELECT").attr("disabled","disabled");
</script>

