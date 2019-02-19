<?
/*
 * Revised: Oct 17, 2011
 */

?>
<table width="100%" cellspacing="2">
<tr>
    <td valign='top' width="20%" nowrap style="padding-right:20px">
        <fieldset>
            <legend>Search By</legend>
            <div class="fieldset">
                <table width="100%">
                <tr>
                    <td width="16%">
                        Confirmed<br>
                        <select name="IS_CONFIRMED" ID="IS_CONFIRMED">
                            <option value=""></option>
                            <option value="1" <? if (isset($IS_CONFIRMED)&&$IS_CONFIRMED=="1") print "selected" ?>>Yes</option>
                            <option value="0" <? if (isset($IS_CONFIRMED)&&$IS_CONFIRMED=="0") print "selected" ?>>No</option>
                        </select>
                    </td>
                    <td width="17%">IATA<br><input type="text" name="IATA" ID="IATA" style="width:100px" value="<? print isset($IATA) ? $IATA : "" ?>"></td>
                    <td width="33%">Agency Name<br><input type="text" name="AGENCY" ID="AGENCY" style="width:220px" value="<? print isset($AGENCY) ? $AGENCY : "" ?>"></td>
                    <td width="33%">Agency Phone Number<br><input type="text" name="PHONE" ID="PHONE" style="width:220px" value="<? print isset($PHONE) ? $PHONE : "" ?>"></td>
                </tr>
                <tr>
                    <td width="33%" colspan="2">Agent Last Name<br><input type="text" name="LASTNAME" ID="LASTNAME" style="width:220px" value="<? print isset($LASTNAME) ? $LASTNAME : "" ?>"></td>
                    <td width="33%">Agent Email<br><input type="text" name="EMAIL" ID="EMAIL" style="width:220px" value="<? print isset($EMAIL) ? $EMAIL : "" ?>"></td>
                    <td width="33%" rowspan="3">
                        <table>
                        <tr>
                            <td><a onclick="$('#EXPORT').val('0');$('#ACTION').val('SUBMIT');$('#pageNo').val('1');$('#editfrm').submit()"><span class="button key">Search</span></a></td>
                            <td><a onclick="document.location.href='?PAGE_CODE=edit_ta'"><span class="button key">New</span></a></td>
                        </tr>
                        <tr><td><br></td></tr>
                        <tr>
                            <td><a onclick="$('#EXPORT').val('1');$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Export</span></a></td>
                            <td><a onclick="$('#EXPORT').val('0');$('#ACTION').val('IMPORT');$('#editfrm').submit()"><span class="button key">Import</span></a></td>
                        </tr>
                        </table>
                    </td>
                </tr>
                </table>
            </div>
        </fieldset>
    </td>
</tr>
</table>
