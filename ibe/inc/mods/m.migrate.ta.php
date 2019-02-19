<?
/*
 * Revised: Sep 07, 2011
 */

?>

<table width="100%" cellspacing="4" cellpadding="1">
<tr>
    <td colspan="10">
        <h3 class="h3_hdr">AGENCY INFORMATION</h3>
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        IATA
    </td>
    <td width="100%" valign="top">
        <input type="text" id="IATA" name="IATA" class="med" title="Agency IATA" value="<? print isset($TA['IATA'])?$TA['IATA']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Agency name
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_NAME" class="large" title="Agency Name" name="AGENCY_NAME" value="<? print isset($TA['AGENCY_NAME'])?$TA['AGENCY_NAME']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Agency Phone
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_PHONE" class="large" title="Agency Phone" name="AGENCY_PHONE" value="<? print isset($TA['AGENCY_PHONE'])?$TA['AGENCY_PHONE']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Address
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_ADDRESS" class="large" title="Address" name="AGENCY_ADDRESS" value="<? print isset($TA['AGENCY_ADDRESS'])?$TA['AGENCY_ADDRESS']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Country
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_COUNTRY" class="large" title="Country" name="AGENCY_COUNTRY" value="<? print isset($TA['AGENCY_COUNTRY'])?$TA['AGENCY_COUNTRY']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        State/Province
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_STATE" class="med" title="State/Province" name="AGENCY_STATE" value="<? print isset($TA['AGENCY_STATE'])?$TA['AGENCY_STATE']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        City
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_CITY" class="med" title="City" name="AGENCY_CITY" value="<? print isset($TA['AGENCY_CITY'])?$TA['AGENCY_CITY']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Zip/Postal code
    </td>
    <td width="100%" valign="top">
        <input type="text" id="AGENCY_ZIPCODE" class="med" title="Zip/Postal code" name="AGENCY_ZIPCODE" value="<? print isset($TA['AGENCY_ZIPCODE'])?$TA['AGENCY_ZIPCODE']:"" ?>">
    </td>
</tr>
<tr>
    <td colspan="10">
        <h3 class="h3_hdr">CONTACT INFORMATION</h3>
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        First Name
    </td>
    <td width="100%" valign="top">
        <input type="text" id="FIRSTNAME" class="large" title="First Name" name="FIRSTNAME" value="<? print isset($TA['FIRSTNAME'])?$TA['FIRSTNAME']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        Last Name
    </td>
    <td width="100%" valign="top">
        <input type="text" id="LASTNAME" class="large" title="Last Name" name="LASTNAME" value="<? print isset($TA['LASTNAME'])?$TA['LASTNAME']:"" ?>">
    </td>
</tr>
<tr>
    <td valign="top" nowrap>
        E-mail
    </td>
    <td width="100%" valign="top">
        <input type="text" id="EMAIL" class="large" title="E-mail" name="EMAIL" value="<? print isset($TA['EMAIL'])?$TA['EMAIL']:"" ?>">
    </td>
</tr>
</table>
