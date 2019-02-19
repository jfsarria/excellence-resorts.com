<?
/*
 * Revised: Sep 06, 2011
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
                    <td width="50%">Last Name<br><input type="text" name="LASTNAME" ID="LASTNAME" style="width:240px" value="<? print isset($LASTNAME) ? $LASTNAME : "" ?>"></td>
                    <td width="50%">Phone Number<br><input type="text" name="PHONE" ID="PHONE" style="width:240px" value="<? print isset($PHONE) ? $PHONE : "" ?>"></td>
                    <td width="50%">
                        Mailing List<br>
                        <select name="MAILING_LIST" ID="MAILING_LIST">
                            <option value=""></option>
                            <option value="1" <? if (isset($MAILING_LIST)&&$MAILING_LIST=="1") print "selected" ?>>Yes</option>
                            <option value="0" <? if (isset($MAILING_LIST)&&$MAILING_LIST=="0") print "selected" ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="50%">Email<br><input type="text" name="EMAIL" ID="EMAIL" style="width:240px" value="<? print isset($EMAIL) ? $EMAIL : "" ?>"></td>
                    <td width="50%" rowspan="3">
                        <a onclick="$('#EXPORT').val('0');$('#ACTION').val('SUBMIT');$('#pageNo').val('1');$('#editfrm').submit()"><span class="button key">Search</span></a>
                        &nbsp;&nbsp;&nbsp;
                        <a onclick="$('#EXPORT').val('1');$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Export to Excel</span></a>
                        &nbsp;&nbsp;&nbsp;
                        <a onclick="$('#EXPORT').val('0');$('#ACTION').val('IMPORT');$('#editfrm').submit()" id='guest_import_btn'><span class="button key">Import</span></a>
                    </td>
                </tr>
                </table>
            </div>
        </fieldset>
    </td>
</tr>
</table>
