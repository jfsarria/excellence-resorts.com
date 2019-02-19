<?
/*
 * Revised: Aug 09, 2011
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
                    <td width="50%">Email<br><input type="text" name="EMAIL" ID="EMAIL" style="width:240px" value="<? print isset($EMAIL) ? $EMAIL : "" ?>"></td>
                    <td width="50%" rowspan="3">
                        <a onclick="$('#ACTION').val('SUBMIT');$('#editfrm').submit()"><span class="button key">Search</span></a>
                        &nbsp;&nbsp;
                        <a onclick="document.location.href='?PAGE_CODE=edit_ccagent'"><span class="button key">New</span></a>
                    </td>
                </tr>
                </table>
            </div>
        </fieldset>
    </td>
</tr>
</table>
