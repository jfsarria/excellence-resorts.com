<?
/*
 * Revised: May 11, 2011
 */
?>
<fieldset>
    <div class="fieldset">
        <div class="label">
            <table width="100%">
            <tr>
                <td nowrap width="80%">
                    Class Belongs to the Year&nbsp;
                    <?
                    if (!isset($_DATA['YEAR'])) {
                        print "<select id='YEAR' name='YEAR'>";
                        for ($t=2011; $t<=date("Y")+2; ++$t) {
                            $selected = (isset($_DATA['YEAR'])&&(int)$_DATA['YEAR']==$t) ? "selected":"";
                            print "<option value='{$t}' $selected>{$t}</option>";
                        }
                        print "</select>";
                    } else {
                        print "
                            <b>{$_DATA['YEAR']}</b>
                            <input type='hidden' id='YEAR' name='YEAR' value='{$_DATA['YEAR']}'>
                        ";
                    }
                    ?>
                </td>
                <? if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) { ?>
                <td nowrap width="10%">Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>></span>&nbsp;&nbsp;&nbsp;</td>
                <td nowrap width="10%">Archive&nbsp;<span><input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA['IS_ARCHIVE'])&&(int)$_DATA['IS_ARCHIVE']==1) ? "checked" : "" ?>></span></td>
                <? } ?>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>
